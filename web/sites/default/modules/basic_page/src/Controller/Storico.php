<?php

namespace Drupal\basic_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for basic_page utility routes.
 */
class Storico extends ControllerBase {

  /**
   * Custom access denied page.
   */
  public function access_denied() {
    // You can customize this markup to match your old project.
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Access denied.'),
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Custom not found page.
   */
  public function not_found() {
    // Either render a custom 404 page or throw a NotFound exception.
    // For now we throw the exception so Drupal shows the standard 404.
    throw new NotFoundHttpException();
  }

  /**
   * Placeholder for check-data route.
   */
  public function ceck_data() {
    // TODO: Port real logic from the EYCA project if needed.
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Check data (placeholder).'),
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Placeholder for seek route.
   */
  public function seek() {
    // TODO: Port real logic from the EYCA project if needed.
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Seek page (placeholder).'),
      '#cache' => ['max-age' => 0],
    ];
  }

}
