<?php

namespace Drupal\basic_page\Traits;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Utils\CasiStudio;
use Drupal\file\Entity\File;

/**
 * Trait for fetching and processing step data.
 */
trait StepDataTrait {

  use BloccoHomeTrait;

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

    // Fetch blocco_home sections by name using BloccoHomeTrait
    $blocco_home_map = [
      'blocco_home' => 'Credenziali digitali',
      'blocco_home_funziona' => 'Come funziona',
      'blocco_home_obv' => 'Cosa puoi fare con Obv',
      'blocco_inizia_ora' => 'Inizia Ora',
    ];
    $data = array_merge($data, $this->fetchMultipleBloccoHome($blocco_home_map, $account));

    return $data;
  }

}
