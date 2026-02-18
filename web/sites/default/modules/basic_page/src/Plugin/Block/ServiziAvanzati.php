<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;

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

  use BloccoHomeTrait;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $data['title'] = 'Servizi avanzati';
    $data['theme'] = 'servizi_avanzati_render';

    $account = \Drupal::currentUser();

    // Fetch Servizi Avanzati block
    $blocco_servizi_avanzati = $this->fetchBloccoHome('Servizi Avanzati', $account);
    if ($blocco_servizi_avanzati) {
      $data['blocco_servizi_avanzati'] = $blocco_servizi_avanzati;
    }

    // Fetch Inizia Ora block
    $blocco_inizia_ora = $this->fetchBloccoHome('Inizia Ora', $account);
    if ($blocco_inizia_ora) {
      $data['blocco_inizia_ora'] = $blocco_inizia_ora;
    }

    $build = [];
    $build['#theme'] = $data['theme'];
    $build['#data'] = $data;
    $build['#title'] = '';

    return $build;
  }

}
