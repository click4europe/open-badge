<?php

namespace Drupal\basic_page\Traits;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Utils\CasiStudio;
use Drupal\block_content\Entity\BlockContent;
use Drupal\file\Entity\File;

/**
 * Trait for fetching and processing step data.
 */
trait StepDataTrait {

  /**
   * Fetch and process step data.
   *
   * @param string $lang
   *   The language code.
   * @param int $limit
   *   The number of steps to fetch (default: 6).
   *
   * @return array
   *   Array containing 'steps' and 'steps_edit' keys.
   */
  protected function getStepData($lang, $limit = 6) {
    $data = [];
    $account = \Drupal::currentUser();

    // Fetch blocco_step
    $steps = Blocchi::make_query_blocchi('blocco_step', $lang, TRUE, 'ASC', 0, $limit);
    
    // Resolve video file IDs to URLs
    $fileUrlGenerator = \Drupal::service('file_url_generator');
    if (!empty($steps)) {
      foreach ($steps as &$step) {
        if (!empty($step->video_fid)) {
          $file = File::load($step->video_fid);
          if ($file) {
            $step->video = $fileUrlGenerator->generateAbsoluteString($file->getFileUri());
          }
        }
      }
    }
    $data['steps'] = $steps;
    
    // Add edit link for admin (user ID 1)
    if ($account->id() == 1) {
      $options = [
        'absolute' => TRUE,
        'query' => ['type' => 'blocco_step'],
        'attributes' => ['class' => ['button text-slate-900 underline']]
      ];
      $url = Url::fromUri('internal:/admin/content/block', $options);
      $data['steps_edit'] = Link::fromTextAndUrl('Configura Steps', $url);
    }

    // Fetch case studies (latest 3)
    try {
      $casi = CasiStudio::casi_studio_list('DESC', 0, 3, '');
      $data['casi_studio'] = !empty($casi['row']) ? $casi['row'] : [];
    } catch (\Exception $e) {
      $data['casi_studio'] = [];
    }

    // Admin edit link for case studies
    if ($account->id() == 1 || in_array('administrator', $account->getRoles())) {
      $options = [
        'absolute' => TRUE,
        'query' => ['type' => 'casi_studio'],
        'attributes' => ['class' => ['button text-slate-900 underline']]
      ];
      $url = Url::fromUri('internal:/admin/content', $options);
      $data['casi_studio_edit'] = Link::fromTextAndUrl('Configura Casi Studio', $url);
    }

    // Fetch blocco_home sections by name
    $blocco_home_map = [
      'blocco_home' => 'Credenziali digitali',
      'blocco_home_funziona' => 'Come funziona',
      'blocco_home_obv' => 'Cosa puoi fare con Obv',
    ];
    foreach ($blocco_home_map as $key => $info_name) {
      $ids = \Drupal::entityQuery('block_content')
        ->condition('type', 'blocco_home')
        ->condition('info', $info_name)
        ->range(0, 1)
        ->accessCheck(FALSE)
        ->execute();
      if (!empty($ids)) {
        $data[$key] = $this->makePrintBlock(reset($ids), $account);
      }
    }

    return $data;
  }

  /**
   * Load and format a block content entity for rendering.
   */
  protected function makePrintBlock($id, $account = NULL) {
    $rend = [];
    $block = BlockContent::load($id);
    if (!$block) {
      return $rend;
    }

    $rend['titolo'] = $block->get('field_titolo')->getValue();
    $rend['sub_title'] = $block->get('field_sotto_titolo')->getValue();
    $rend['body'] = $block->get('body')->getValue();
    $rend['immagine'] = $block->get('field_immagine')->getValue();

    if (!empty($rend['immagine'][0]['target_id'])) {
      $file = File::load($rend['immagine'][0]['target_id']);
      if ($file) {
        $fileUrlGenerator = \Drupal::service('file_url_generator');
        $rend['immagine_url'] = $fileUrlGenerator->generateAbsoluteString($file->getFileUri());
        $rend['immagine_alt'] = $rend['immagine'][0]['alt'] ?? '';
      }
    }

    if ($block->hasField('field_second_description') && !$block->get('field_second_description')->isEmpty()) {
      $rend['second_description'] = $block->get('field_second_description')->getValue();
    }

    if ($account && $account->id() == 1) {
      $options = ['absolute' => TRUE, 'query' => ['destination' => 'home'], 'attributes' => ['class' => ['button']]];
      $url = Url::fromUri('internal:/admin/content/block/' . $id, $options);
      $rend['link_edit'] = Link::fromTextAndUrl('Configura Blocco Home', $url);
    }

    return $rend;
  }

}
