<?php

namespace Drupal\basic_page\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns the admin-only test page for the new Abbonamenti design.
 */
class AbbonamentiTestController extends ControllerBase {

  /**
   * Builds the abbonamenti test page.
   */
  public function page() {
    $block_manager = \Drupal::service('plugin.manager.block');
    $plugin_block = $block_manager->createInstance('abbonamenti_test_render', []);
    $render = $plugin_block->build();
    $render['#title'] = $this->t('Prezzi e abbonamenti (nuovo design)');
    return $render;
  }

}
