<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'TermsRender' block.
 *
 * @Block(
 *  id = "terms_render",
 *  admin_label = @Translation("Terms Render"),
 * )
 */
class Terms extends BlockBase
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

        // Fetch hero section data using trait (page_id = 56)
        $data = $this->getStepData($lang, 56);
        
        // Fetch terms block content (Terms Block - type: blocco_home)
        $data['terms_block'] = $this->fetchBloccoHome('Terms Block', $account);

        // Query for recent Notizie nodes and process them
        $query = \Drupal::entityQuery('node')
            ->condition('type', 'notizie')
            ->condition('status', 1)
            ->sort('created', 'DESC')
            ->range(0, 2)
            ->accessCheck(TRUE);
        $nids = $query->execute();
        
        if (!empty($nids)) {
            $node_storage = \Drupal::entityTypeManager()->getStorage('node');
            $nodes = $node_storage->loadMultiple($nids);
            $data['notizie_sidebar'] = [];
            foreach ($nodes as $node) {
                $data['notizie_sidebar'][] = \Drupal\basic_page\Utils\Notizie::document($node);
            }
        }

        $build = [];
        $build['#theme'] = 'terms_render';
        $build['#data'] = $data;
        return $build;
    }
}
