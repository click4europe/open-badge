<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $data['title'] = 'Cosa sono gli Open Badges';
    $data['theme'] = 'cosa_sono_gli_open_badges_render';

    $build = [];
    $build['#theme'] = $data['theme'];
    $build['#data'] = $data;
    $build['#title'] = '';

    return $build;
  }

}
