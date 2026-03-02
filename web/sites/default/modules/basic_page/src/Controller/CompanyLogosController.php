<?php

namespace Drupal\basic_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for Company Logos management.
 */
class CompanyLogosController extends ControllerBase {

  /**
   * Display the company logos management page.
   */
  public function managePage() {
    // Load current data
    $json_file_path = \Drupal::service('extension.list.module')->getPath('basic_page') . '/src/Data/company-logos.json';
    $data = [];
    
    if (file_exists($json_file_path)) {
      $json_content = file_get_contents($json_file_path);
      $data = json_decode($json_content, true) ?: [];
    }

    return [
      '#theme' => 'company_logos_manager',
      '#section_title' => $data['section_title'] ?? '',
      '#logos_per_page' => $data['logos_per_page'] ?? 25,
      '#companies' => $data['companies'] ?? [],
      '#attached' => [
        'library' => [
          'basic_page/company_logos_manager',
        ],
      ],
    ];
  }

  /**
   * Get all companies as JSON.
   */
  public function getCompanies() {
    $json_file_path = \Drupal::service('extension.list.module')->getPath('basic_page') . '/src/Data/company-logos.json';
    $data = [];
    
    if (file_exists($json_file_path)) {
      $json_content = file_get_contents($json_file_path);
      $data = json_decode($json_content, true) ?: [];
    }

    return new JsonResponse($data);
  }

  /**
   * Save company data.
   */
  public function saveCompany(Request $request) {
    $json_file_path = \Drupal::service('extension.list.module')->getPath('basic_page') . '/src/Data/company-logos.json';
    
    // Load current data
    $data = [];
    if (file_exists($json_file_path)) {
      $json_content = file_get_contents($json_file_path);
      $data = json_decode($json_content, true) ?: [];
    }

    // Get posted data
    $posted_data = json_decode($request->getContent(), true);
    $action = $posted_data['action'] ?? '';
    
    if ($action === 'update_settings') {
      $data['section_title'] = $posted_data['section_title'] ?? $data['section_title'];
      $data['logos_per_page'] = (int) ($posted_data['logos_per_page'] ?? $data['logos_per_page']);
    }
    elseif ($action === 'add_company') {
      if (!isset($data['companies'])) {
        $data['companies'] = [];
      }
      $data['companies'][] = [
        'name' => $posted_data['name'] ?? '',
        'logo_url' => $posted_data['logo_url'] ?? '',
        'company_url' => $posted_data['company_url'] ?? '',
        'badge_url' => $posted_data['badge_url'] ?? '',
      ];
    }
    elseif ($action === 'update_company') {
      $index = (int) ($posted_data['index'] ?? -1);
      if ($index >= 0 && isset($data['companies'][$index])) {
        $data['companies'][$index] = [
          'name' => $posted_data['name'] ?? '',
          'logo_url' => $posted_data['logo_url'] ?? '',
          'company_url' => $posted_data['company_url'] ?? '',
          'badge_url' => $posted_data['badge_url'] ?? '',
        ];
      }
    }
    elseif ($action === 'delete_company') {
      $index = (int) ($posted_data['index'] ?? -1);
      if ($index >= 0 && isset($data['companies'][$index])) {
        array_splice($data['companies'], $index, 1);
      }
    }

    // Save to file
    $json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $success = file_put_contents($json_file_path, $json_content);

    if ($success) {
      // Clear cache
      \Drupal::service('cache.render')->invalidateAll();
      return new JsonResponse(['success' => true, 'message' => 'Saved successfully']);
    }

    return new JsonResponse(['success' => false, 'message' => 'Failed to save'], 500);
  }

}
