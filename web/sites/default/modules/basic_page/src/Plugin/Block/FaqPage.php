<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'FaqPage' block.
 *
 * @Block(
 *  id = "faq_page",
 *  admin_label = @Translation("FAQ Page"),
 * )
 */
class FaqPage extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        $account = \Drupal::currentUser();

        // Fetch FAQ Section block
        $data['faq_section'] = $this->fetchFaqSection($account);

        // Render the template
        $build = [];
        $build['#theme'] = 'faq_page_render';
        $build['#data'] = $data;
        return $build;
    }

    /**
     * Fetch FAQ Section block data.
     */
    private function fetchFaqSection($account) {
        $block_storage = \Drupal::entityTypeManager()->getStorage('block_content');
        
        // Query for FAQ Section block by description
        $query = $block_storage->getQuery()
            ->condition('type', 'faq_section')
            ->condition('info', 'FAQ Page')
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
        
        $faq_data = [
            'section_title' => '',
            'faq_items' => [],
            'link_edit' => NULL,
        ];
        
        // Get section title
        if ($block->hasField('field_section_title') && !$block->get('field_section_title')->isEmpty()) {
            $faq_data['section_title'] = $block->get('field_section_title')->value;
        }
        
        // Get FAQ items
        if ($block->hasField('field_faq_items') && !$block->get('field_faq_items')->isEmpty()) {
            foreach ($block->get('field_faq_items')->referencedEntities() as $index => $faq_paragraph) {
                $faq_item = [
                    'index' => $index + 1,
                    'question' => '',
                    'answer' => '',
                ];
                
                // Get FAQ question (title)
                if ($faq_paragraph->hasField('field_faq_title') && !$faq_paragraph->get('field_faq_title')->isEmpty()) {
                    $faq_item['question'] = $faq_paragraph->get('field_faq_title')->value;
                }
                
                // Get FAQ answer (description)
                if ($faq_paragraph->hasField('field_faq_description') && !$faq_paragraph->get('field_faq_description')->isEmpty()) {
                    $faq_item['answer'] = $faq_paragraph->get('field_faq_description')->value;
                }
                
                $faq_data['faq_items'][] = $faq_item;
            }
        }
        
        // Add edit link if user has permission
        if ($account->hasPermission('administer blocks') || $account->hasPermission('edit any block content')) {
            $url = \Drupal\Core\Url::fromRoute('entity.block_content.edit_form', [
                'block_content' => $block->id(),
            ], [
                'query' => ['destination' => '/faq'],
            ]);
            $faq_data['link_edit'] = \Drupal\Core\Link::fromTextAndUrl(t('Edit FAQ'), $url)->toString();
        }
        
        return $faq_data;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [];
    }
}
