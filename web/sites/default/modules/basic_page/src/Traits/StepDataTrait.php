<?php

namespace Drupal\basic_page\Traits;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\blocchi\Utils\Blocchi;

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
    if (!empty($steps)) {
      foreach ($steps as &$step) {
        if (!empty($step->video_fid)) {
          $file = \Drupal\file\Entity\File::load($step->video_fid);
          if ($file) {
            $step->video = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
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

    return $data;
  }

}
