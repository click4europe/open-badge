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

    return $data;
  }

}
