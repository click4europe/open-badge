<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'WebformPageRender' block.
 *
 * @Block(
 *   id = "webform_page_render",
 *   admin_label = @Translation("Webform page render"),
 *   category = @Translation("Custom"),
 * )
 */
class WebformPageRender extends BlockBase {
    
    /**
     * {@inheritdoc}
     */
    public function build() {
        $data = [];
        $node = \Drupal::routeMatch()->getParameter('node');
        
        if (!$node) {
            return [];
        }
        
        // Get node data
        $data['scheda'] = $this->getNodeData($node);
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();
        
        $build = [];
        $build['#theme'] = 'webform_page_render';
        $build['#data'] = $data;
        $build['#title'] = '';
        $build['#target_id'] = '';
        
        // Get webform if exists and is open
        if ($node->hasField('field_webform') && !$node->get('field_webform')->isEmpty()) {
            $webform = $node->get('field_webform')->entity;
            if ($webform && $webform->status() == 'open') {
                $build['#target_id'] = $webform->id();
            }
        }
        
        // Attach library for accordion if needed
        $build['#attached']['library'][] = 'basic_page/basic_page.accordion';
        
        return $build;
    }
    
    /**
     * Get node data in required format.
     */
    private function getNodeData($node) {
        $data = [];
        
        $data['id'] = $node->id();
        $data['title'] = $node->getTitle();
        $data['bundle'] = $node->getType();
        $data['created'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'short');
        
        // Handle image
        if ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
            $image_uri = $node->get('field_image')->entity->getFileUri();
            $data['image']['path'] = \Drupal::service('file_url_generator')->generateAbsoluteString($image_uri);
            $data['image']['alt'] = $node->get('field_image')->alt ?: '';
            $data['image']['title'] = $node->get('field_image')->title ?: '';
        }
        
        // Handle body
        if ($node->hasField('body') && !$node->get('body')->isEmpty()) {
            $data['body'] = $node->get('body')->value;
        }
        
        // Handle subtitle
        if ($node->hasField('field_subtitle') && !$node->get('field_subtitle')->isEmpty()) {
            $data['sotto_titolo'][0]['value'] = $node->get('field_subtitle')->value;
        }
        
        // Handle FAQ
        if ($node->hasField('field_faq') && !$node->get('field_faq')->isEmpty()) {
            $data['field_faq'] = [];
            foreach ($node->get('field_faq') as $item) {
                if ($item->entity) {
                    $data['field_faq'][] = [
                        'question' => $item->entity->get('field_question')->value,
                        'answer' => $item->entity->get('field_answer')->value,
                    ];
                }
            }
        }
        
        // Handle custom blocks for sidebar
        if ($node->hasField('field_custom_blocks') && !$node->get('field_custom_blocks')->isEmpty()) {
            $data['personalizzati'] = $node->get('field_custom_blocks')->getValue();
        }
        
        return $data;
    }
}