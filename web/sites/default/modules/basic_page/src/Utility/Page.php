<?php

namespace Drupal\basic_page\Utility;

use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Utility class to extract node fields into a plain array.
 */
class Page {

  /**
   * Extract all fields from a Basic Page node into an array.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return array
   *   Array containing all node field data.
   */
  public static function document($node) {
    if (!$node) {
      return [];
    }

    $data = [];

    // Basic fields
    $data['title'] = $node->getTitle();
    $data['nid'] = $node->id();
    
    // Body field
    if ($node->hasField('body') && !$node->get('body')->isEmpty()) {
      $data['body'] = $node->get('body')->value;
    }

    // Sotto Titolo
    if ($node->hasField('field_sotto_titolo') && !$node->get('field_sotto_titolo')->isEmpty()) {
      $data['sotto_titolo'] = $node->get('field_sotto_titolo')->getValue();
    }

    // Main Image
    if ($node->hasField('field_immagine') && !$node->get('field_immagine')->isEmpty()) {
      $fid = $node->get('field_immagine')->target_id;
      if ($fid) {
        $file = File::load($fid);
        if ($file) {
          $data['image'] = [
            'fid' => $fid,
            'path' => \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()),
            'alt' => $node->get('field_immagine')->alt ?? '',
          ];
        }
      }
    }

    // Hero fields
    if ($node->hasField('field_hero_title') && !$node->get('field_hero_title')->isEmpty()) {
      $data['hero_title'] = $node->get('field_hero_title')->value;
    }

    if ($node->hasField('field_hero_description') && !$node->get('field_hero_description')->isEmpty()) {
      $data['hero_description'] = $node->get('field_hero_description')->value;
    }

    if ($node->hasField('field_hero_compact') && !$node->get('field_hero_compact')->isEmpty()) {
      $data['hero_compact'] = $node->get('field_hero_compact')->value;
    }

    // Hero Image
    if ($node->hasField('field_hero_image') && !$node->get('field_hero_image')->isEmpty()) {
      $fid = $node->get('field_hero_image')->target_id;
      if ($fid) {
        $file = File::load($fid);
        if ($file) {
          $data['hero_image'] = [
            'fid' => $fid,
            'path' => \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()),
            'alt' => $node->get('field_hero_image')->alt ?? '',
          ];
        }
      }
    }

    // Hero block entity reference (if using page_hero block type)
    if ($node->hasField('field_hero_section') && !$node->get('field_hero_section')->isEmpty()) {
      $data['hero_id'] = $node->get('field_hero_section')->target_id;
    }

    // Sidebar blocks (blocchi spalla)
    if ($node->hasField('field_blocchi_spalla') && !$node->get('field_blocchi_spalla')->isEmpty()) {
      $data['personalizzati'] = $node->get('field_blocchi_spalla')->getValue();
    }

    // Notizie sidebar (manual selection)
    if ($node->hasField('field_notizie_sidebar') && !$node->get('field_notizie_sidebar')->isEmpty()) {
      $data['notizie_sidebar_refs'] = $node->get('field_notizie_sidebar')->getValue();
    }

    // FAQ field (Domande Frequenti)
    if ($node->hasField('field_domande_frequenti') && !$node->get('field_domande_frequenti')->isEmpty()) {
      $faq_items = [];
      $raw_values = $node->get('field_domande_frequenti')->getValue();
      
      foreach ($raw_values as $delta => $value) {
        $faq_item = [];
        
        // Handle different field structures
        if (isset($value['target_id'])) {
          // Entity reference - load the referenced entity
          $entity_type = $node->get('field_domande_frequenti')->getFieldDefinition()->getSetting('target_type');
          $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->load($value['target_id']);
          
          if ($entity) {
            // Try to get question and answer from the entity
            $question_fields = ['field_question', 'field_domanda', 'field_titolo', 'field_title', 'name', 'title'];
            $answer_fields = ['field_answer', 'field_risposta', 'field_body', 'body', 'field_description'];
            
            foreach ($question_fields as $field_name) {
              if ($entity->hasField($field_name) && !$entity->get($field_name)->isEmpty()) {
                $faq_item['question'] = $entity->get($field_name)->value;
                break;
              }
            }
            
            foreach ($answer_fields as $field_name) {
              if ($entity->hasField($field_name) && !$entity->get($field_name)->isEmpty()) {
                $faq_item['answer'] = $entity->get($field_name)->value;
                break;
              }
            }
          }
        }
        elseif (isset($value['value'])) {
          // Simple text field - use value as question
          $faq_item['question'] = $value['value'];
          $faq_item['answer'] = isset($value['format']) ? '' : $value['value'];
        }
        elseif (isset($value['question']) && isset($value['answer'])) {
          // Field with question/answer structure
          $faq_item['question'] = $value['question'];
          $faq_item['answer'] = $value['answer'];
        }
        
        // Add item if we have at least a question
        if (!empty($faq_item['question'])) {
          if (empty($faq_item['answer'])) {
            $faq_item['answer'] = '';
          }
          $faq_items[] = $faq_item;
        }
      }
      
      if (!empty($faq_items)) {
        $data['faq'] = $faq_items;
      }
    }

    return $data;
  }

}
