<?php

declare(strict_types=1);

namespace Drupal\rebuild_cache_access;

use Drupal\Core\Routing\RedirectDestinationInterface;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Lazy builders for the rebuild cache links.
 *
 * The links carry the current page as destination, so they are rendered in
 * placeholders to keep the per-URL variation out of the cached toolbar and
 * navigation.
 */
final class LinkLazyBuilder implements TrustedCallbackInterface {

  use StringTranslationTrait;

  /**
   * Constructs a LinkLazyBuilder object.
   *
   * @param \Drupal\Core\Routing\RedirectDestinationInterface $redirectDestination
   *   The redirect destination service.
   */
  public function __construct(
    private readonly RedirectDestinationInterface $redirectDestination,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks(): array {
    return ['renderToolbarLink', 'renderNavigationLink'];
  }

  /**
   * Renders the toolbar link back to the current page.
   *
   * @return array
   *   The link render array.
   */
  public function renderToolbarLink(): array {
    return [
      '#type' => 'link',
      '#title' => $this->t('Rebuild Cache'),
      '#url' => $this->buildUrl(),
      '#attributes' => [
        'title' => $this->t('Rebuild Cache Access'),
        'class' => [
          'toolbar-item',
          'toolbar-icon',
          'toolbar-icon-rebuild-cache-access',
        ],
      ],
      '#cache' => [
        'contexts' => ['url'],
      ],
    ];
  }

  /**
   * Renders the Navigation link back to the current page.
   *
   * @param string $label
   *   The block label.
   *
   * @return array
   *   The navigation menu render array.
   */
  public function renderNavigationLink(string $label): array {
    return [
      'rebuild_cache_access' => [
        '#title' => '',
        '#theme' => 'navigation_menu',
        '#menu_name' => 'link',
        '#items' => [
          [
            'title' => $label,
            'url' => $this->buildUrl(),
            'class' => 'system-admin-config',
            'icon' => [
              'icon_id' => 'system-admin-config',
            ],
          ],
        ],
      ],
      '#title' => $label,
      '#cache' => [
        'contexts' => ['url'],
      ],
    ];
  }

  /**
   * Builds the rebuild cache URL with the current page as destination.
   *
   * @return \Drupal\Core\Url
   *   The rebuild cache URL.
   */
  private function buildUrl(): Url {
    return Url::fromRoute('rebuild_cache_access.rebuild_cache', [], [
      'query' => $this->redirectDestination->getAsArray(),
    ]);
  }

}
