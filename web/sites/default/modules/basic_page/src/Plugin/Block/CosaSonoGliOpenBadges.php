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

    $build = [];
    $build['#theme'] = $data['theme'];
    $build['#data'] = $data;
    $build['#title'] = '';

    return $build;
  }

}
