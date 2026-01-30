<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Servizi Avanzati' Block.
 *
 * @Block(
 *   id = "servizi_avanzati",
 *   admin_label = @Translation("Servizi Avanzati"),
 *   category = @Translation("Custom"),
 * )
 */
class ServiziAvanzati extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $data['title'] = 'Servizi avanzati';
    $data['theme'] = 'servizi_avanzati_render';

    $build = [];
    $build['#theme'] = $data['theme'];
    $build['#data'] = $data;
    $build['#title'] = '';

    return $build;
  }

}
