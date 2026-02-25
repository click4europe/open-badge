<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\BloccoHomeTrait;

/**
 * Provides a 'VantaggiOpenBadge' block.
 *
 * @Block(
 *  id = "vantaggi_open_badge",
 *  admin_label = @Translation("Vantaggi Open Badge"),
 * )
 */
class VantaggiOpenBadge extends BlockBase
{
    use BloccoHomeTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        $account = \Drupal::currentUser();
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // Fetch multiple blocco_home blocks using trait
        $blocks_map = [
            'hero' => 'Vantaggi Hero',
            'info_section' => 'Informazioni sugli Open Badge',
        ];
        $data = array_merge($data, $this->fetchMultipleBloccoHome($blocks_map, $account));

        // Fetch Benefits Section block
        $data['benefits_section'] = $this->fetchBenefitsSection($account);

        // Render the template
        $build = [];
        $build['#theme'] = 'vantaggi_open_badge_render';
        $build['#data'] = $data;
        return $build;
    }

    /**
     * Fetch Benefits Section block data.
     */
    private function fetchBenefitsSection($account) {
        $block_storage = \Drupal::entityTypeManager()->getStorage('block_content');
        
        // Query for Benefits Section block by description
        $query = $block_storage->getQuery()
            ->condition('type', 'benefits_section')
            ->condition('info', 'lista di motivi')
            ->accessCheck(TRUE)
            ->range(0, 1);
        
        $ids = $query->execute();
        
        if (empty($ids)) {
            return NULL;
        }
        
        $block_id = reset($ids);
        $block = $block_storage->load($block_id);
        
        if (!$block) {
            return NULL;
        }
        
        $benefits_data = [
            'section_title' => '',
            'sub_title' => '',
            'second_title' => '',
            'benefit_items' => [],
            'link_edit' => NULL,
        ];
        
        // Get section title
        if ($block->hasField('field_section_title') && !$block->get('field_section_title')->isEmpty()) {
            $benefits_data['section_title'] = $block->get('field_section_title')->value;
        }
        
        // Get subtitle
        if ($block->hasField('field_sotto_titolo') && !$block->get('field_sotto_titolo')->isEmpty()) {
            $benefits_data['sub_title'] = $block->get('field_sotto_titolo')->value;
        }
        
        // Get second title
        if ($block->hasField('field_titolo_secondo') && !$block->get('field_titolo_secondo')->isEmpty()) {
            $benefits_data['second_title'] = $block->get('field_titolo_secondo')->value;
        }
        
        // Get benefit items
        if ($block->hasField('field_benefit_items') && !$block->get('field_benefit_items')->isEmpty()) {
            foreach ($block->get('field_benefit_items')->referencedEntities() as $index => $benefit_paragraph) {
                $benefit_item = [
                    'index' => $index + 1,
                    'title' => '',
                    'description' => '',
                ];
                
                // Get benefit title
                if ($benefit_paragraph->hasField('field_benefit_title') && !$benefit_paragraph->get('field_benefit_title')->isEmpty()) {
                    $benefit_item['title'] = $benefit_paragraph->get('field_benefit_title')->value;
                }
                
                // Get benefit description
                if ($benefit_paragraph->hasField('field_benefit_description') && !$benefit_paragraph->get('field_benefit_description')->isEmpty()) {
                    $benefit_item['description'] = $benefit_paragraph->get('field_benefit_description')->value;
                }
                
                $benefits_data['benefit_items'][] = $benefit_item;
            }
        }
        
        // Add edit link if user has permission
        if ($account->hasPermission('administer blocks') || $account->hasPermission('edit any block content')) {
            $url = \Drupal\Core\Url::fromRoute('entity.block_content.edit_form', [
                'block_content' => $block->id(),
            ], [
                'query' => ['destination' => '/vantaggi-open-badge'],
            ]);
            $benefits_data['link_edit'] = \Drupal\Core\Link::fromTextAndUrl(t('Edit Benefits'), $url)->toString();
        }
        
        return $benefits_data;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [];
    }
}
