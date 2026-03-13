<?php

namespace Drupal\basic_page\Traits;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\block_content\Entity\BlockContent;
use Drupal\file\Entity\File;

/**
 * Trait for fetching and processing blocco_home blocks.
 * 
 * This trait provides reusable methods for loading blocco_home blocks
 * and formatting their data for rendering in templates.
 */
trait BloccoHomeTrait {

  /**
   * Fetch multiple blocco_home blocks by name.
   * 
   * @param array $blocks_map
   *   Associative array where keys are data keys and values are block names.
   *   Example: ['blocco_key' => 'Block Name']
   * @param \Drupal\Core\Session\AccountInterface|null $account
   *   Optional user account for edit link generation.
   * 
   * @return array
   *   Array of processed block data indexed by the provided keys.
   */
  protected function fetchMultipleBloccoHome(array $blocks_map, $account = NULL) {
    $data = [];
    
    foreach ($blocks_map as $key => $block_name) {
      $block_data = $this->fetchBloccoHome($block_name, $account);
      if ($block_data) {
        $data[$key] = $block_data;
      }
    }
    
    return $data;
  }

  /**
   * Fetch a single blocco_home block by name.
   * 
   * @param string $block_name
   *   The block name (info field value).
   * @param \Drupal\Core\Session\AccountInterface|null $account
   *   Optional user account for edit link generation.
   * 
   * @return array|null
   *   Processed block data or NULL if not found.
   */
  protected function fetchBloccoHome($block_name, $account = NULL) {
    // Use static cache to avoid duplicate queries in the same request
    static $cache = [];
    
    $cache_key = $block_name;
    if (isset($cache[$cache_key])) {
      return $cache[$cache_key];
    }
    
    $ids = \Drupal::entityQuery('block_content')
      ->condition('type', 'blocco_home')
      ->condition('info', $block_name)
      ->condition('status', 1)
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    
    if (!empty($ids)) {
      $result = $this->makePrintBlock(reset($ids), $account);
      $cache[$cache_key] = $result;
      return $result;
    }
    
    $cache[$cache_key] = NULL;
    return NULL;
  }

  /**
   * Load and format a block content entity for rendering.
   * 
   * @param int $id
   *   The block content entity ID.
   * @param \Drupal\Core\Session\AccountInterface|null $account
   *   Optional user account for edit link generation.
   * 
   * @return array
   *   Formatted block data with processed fields.
   */
  protected function makePrintBlock($id, $account = NULL) {
    $rend = [];
    $block = BlockContent::load($id);
    
    if (!$block) {
      return $rend;
    }

    // Load standard fields
    $rend['titolo'] = $block->get('field_titolo')->getValue();
    $rend['sub_title'] = $block->get('field_sotto_titolo')->getValue();
    $rend['body'] = $block->get('body')->getValue();
    $rend['immagine'] = $block->get('field_immagine')->getValue();

    // Process image if exists
    if (!empty($rend['immagine'][0]['target_id'])) {
      $file = File::load($rend['immagine'][0]['target_id']);
      if ($file) {
        $fileUrlGenerator = \Drupal::service('file_url_generator');
        $rend['immagine_url'] = $fileUrlGenerator->generateAbsoluteString($file->getFileUri());
        $rend['immagine_alt'] = $rend['immagine'][0]['alt'] ?? '';
      }
    }

    // Add second_description field if it exists
    if ($block->hasField('field_second_description') && !$block->get('field_second_description')->isEmpty()) {
      $rend['second_description'] = $block->get('field_second_description')->getValue();
    }

    // Add first_link field if it exists
    if ($block->hasField('field_first_link') && !$block->get('field_first_link')->isEmpty()) {
      $link_field = $block->get('field_first_link')->first();
      if ($link_field) {
        $rend['first_link'] = [
          [
            'uri' => $link_field->uri,
            'title' => $link_field->title,
            'url' => $link_field->getUrl()->toString(),
          ]
        ];
      }
    }

    // Add second_link field if it exists
    if ($block->hasField('field_second_link') && !$block->get('field_second_link')->isEmpty()) {
      $link_field = $block->get('field_second_link')->first();
      if ($link_field) {
        $rend['second_link'] = [
          [
            'uri' => $link_field->uri,
            'title' => $link_field->title,
            'url' => $link_field->getUrl()->toString(),
          ]
        ];
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
      $url = Url::fromUri('internal:/admin/content/block/' . $id, $options);
      $rend['link_edit'] = Link::fromTextAndUrl('Configura Blocco', $url);
    }

    return $rend;
  }

}
