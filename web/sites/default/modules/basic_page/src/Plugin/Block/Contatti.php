<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'ContattiRender' block.
 *
 * @Block(
 *  id = "contatti_render",
 *  admin_label = @Translation("Contatti Render"),
 * )
 */
class Contatti extends BlockBase
{
    use BloccoHomeTrait;
    use StepDataTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $account = \Drupal::currentUser();

        // Fetch step data using trait
        $data = $this->getStepData($lang, 6);

        // Fetch contact form header block (Contatti Block - type: blocco_home)
        $data['contatti_block'] = $this->fetchBloccoHome('Contatti Block', $account);

        // Fetch contact form block (Contattaci - type: form)
        $data['form_block'] = $this->fetchBlockByInfo('Contattaci', 'form', $account);

        $build = [];
        $build['#theme'] = 'contatti_render';
        $build['#data'] = $data;
        return $build;
    }

    /**
     * Fetch a form block by info name and process its fields.
     */
    protected function fetchBlockByInfo($info_name, $block_type, $account = NULL)
    {
        $ids = \Drupal::entityQuery('block_content')
            ->condition('type', $block_type)
            ->condition('info', $info_name)
            ->condition('status', 1)
            ->range(0, 1)
            ->accessCheck(FALSE)
            ->execute();

        if (empty($ids)) {
            return NULL;
        }

        $block = \Drupal\block_content\Entity\BlockContent::load(reset($ids));
        if (!$block) {
            return NULL;
        }

        $rend = [];

        // Load form-specific fields dynamically
        $field_mapping = [
            'field_titolo' => 'titolo',
            'field_sotto_titolo' => 'sub_title',
            'body' => 'body',
            'field_full_name' => 'field_full_name',
            'field_email' => 'field_email',
            'field_reason' => 'field_reason',
            'field_description' => 'field_description',
            'field_link_privacy' => 'field_link_privacy',
            'field_link_terms' => 'field_link_terms',
            'field_check_personal_data' => 'field_check_personal_data',
            'field_check_terms_and_conditions' => 'field_check_terms_and_conditions',
        ];

        foreach ($field_mapping as $field_name => $key) {
            if ($block->hasField($field_name) && !$block->get($field_name)->isEmpty()) {
                $rend[$key] = $block->get($field_name)->getValue();
            }
        }

        // Add edit link for admin users
        if ($account && $account->id() == 1) {
            $current_path = \Drupal::service('path.current')->getPath();
            $path_alias = \Drupal::service('path_alias.manager')->getAliasByPath($current_path);
            $destination = trim($path_alias, '/');

            $options = [
                'absolute' => TRUE,
                'query' => ['destination' => $destination],
                'attributes' => ['class' => ['button']]
            ];
            $url = \Drupal\Core\Url::fromUri('internal:/admin/content/block/' . $block->id(), $options);
            $rend['link_edit'] = \Drupal\Core\Link::fromTextAndUrl('Configura Blocco', $url);
        }

        return $rend;
    }
}
