<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;

/**
 * Provides a 'Cosa Sono Gli Open Badges' Block.
 *
 * @Block(
 *   id = "cosa_sono_gli_open_badges",
 *   admin_label = @Translation("Cosa Sono Gli Open Badges"),
 *   category = @Translation("Custom"),
 * )
 */
class CosaSonoGliOpenBadges extends BlockBase {

  use BloccoHomeTrait;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $data['title'] = 'Cosa sono gli Open Badges';
    $data['theme'] = 'cosa_sono_gli_open_badges_render';
    
    $account = \Drupal::currentUser();

    // Fetch multiple blocco_home blocks using trait
    $blocks_map = [
      'blocco_tecnologia' => 'La tecnologia Open Badge',
      'blocco_home_obv' => 'Cosa puoi fare con Obv',
      'blocco_home_funziona' => 'Come funziona',
      'blocco_inizia_ora' => 'Inizia Ora',
    ];

    $data = array_merge($data, $this->fetchMultipleBloccoHome($blocks_map, $account));
    
    // Fetch FAQ Section block
    $data['faq_section'] = $this->fetchFaqSection($account);

    $build = [];
    $build['#theme'] = $data['theme'];
    $build['#data'] = $data;
    $build['#title'] = '';

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
      ->condition('info', 'FAQ - Cosa Sono Gli Open Badges')
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
          'title' => '',
          'description' => '',
          'image_url' => '',
          'image_alt' => '',
        ];
        
        // Get FAQ title
        if ($faq_paragraph->hasField('field_faq_title') && !$faq_paragraph->get('field_faq_title')->isEmpty()) {
          $faq_item['title'] = $faq_paragraph->get('field_faq_title')->value;
        }
        
        // Get FAQ description
        if ($faq_paragraph->hasField('field_faq_description') && !$faq_paragraph->get('field_faq_description')->isEmpty()) {
          $faq_item['description'] = $faq_paragraph->get('field_faq_description')->value;
        }
        
        // Get FAQ image
        if ($faq_paragraph->hasField('field_faq_image') && !$faq_paragraph->get('field_faq_image')->isEmpty()) {
          $image = $faq_paragraph->get('field_faq_image')->entity;
          if ($image) {
            $faq_item['image_url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($image->getFileUri());
            $faq_item['image_alt'] = $faq_paragraph->get('field_faq_image')->alt ?: $faq_item['title'];
          }
        }
        
        $faq_data['faq_items'][] = $faq_item;
      }
    }
    
    // Add edit link if user has permission
    if ($account->hasPermission('administer blocks') || $account->hasPermission('edit any block content')) {
      $url = \Drupal\Core\Url::fromRoute('entity.block_content.edit_form', [
        'block_content' => $block->id(),
      ], [
        'query' => ['destination' => '/cosa-sono-gli-open-badges'],
      ]);
      $faq_data['link_edit'] = \Drupal\Core\Link::fromTextAndUrl(t('Edit FAQ Section'), $url)->toString();
    }
    
    return $faq_data;
  }

}
