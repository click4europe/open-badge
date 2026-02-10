<?php

namespace Drupal\basic_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for notizie import helpers.
 */
class ImportNotizieController extends ControllerBase {

  /**
   * Download a CSV template for notizie import.
   */
  public function downloadTemplate() {
    $headers = ['title', 'subtitle', 'body', 'image_path', 'published'];
    $example = [
      'Titolo notizia esempio',
      'Sottotitolo esempio',
      '<p>Testo della notizia in HTML.</p>',
      'immagine-esempio.jpg',
      '1',
    ];

    $output = fopen('php://temp', 'r+');
    // Use semicolon delimiter so Excel displays columns correctly.
    fputcsv($output, $headers, ';');
    fputcsv($output, $example, ';');
    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);

    $response = new Response($csv);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="notizie-import-template.csv"');

    return $response;
  }

}
