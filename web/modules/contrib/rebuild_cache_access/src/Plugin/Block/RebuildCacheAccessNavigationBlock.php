<?php

declare(strict_types=1);

namespace Drupal\rebuild_cache_access\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Security\Attribute\TrustedCallback;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a rebuild cache navigation block.
 *
 * This block is available for use in the Drupal Core Navigation module.
 */
#[Block(
  id: 'rebuild_cache_access_navigation',
  admin_label: new TranslatableMarkup('Rebuild cache'),
)]
class RebuildCacheAccessNavigationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Creates a Rebuild Cache Access Navigation Block instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected AccountInterface $currentUser,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    if ($this->currentUser->hasPermission('rebuild cache access')) {
      return [
        '#create_placeholder' => TRUE,
        '#lazy_builder' => [
          'rebuild_cache_access.link_lazy_builder:renderNavigationLink',
          [$this->configuration['label']],
        ],
        '#cache' => [
          'keys' => ['navigation_rebuild_cache_block'],
          'contexts' => ['user.permissions'],
        ],
      ];
    }

    // No block needed when no access.
    return [
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];
  }

  /**
   * Lazy builder callback.
   *
   * @deprecated in rebuild_cache_access:8.x-1.15 and is removed from
   *   rebuild_cache_access:2.0.0. Use the
   *   rebuild_cache_access.link_lazy_builder service instead.
   *
   * @see https://www.drupal.org/project/rebuild_cache_access/issues/3540172
   */
  #[TrustedCallback]
  public static function buildRebuildCache(string $label): array {
    @trigger_error(__METHOD__ . '() is deprecated in rebuild_cache_access:8.x-1.15 and is removed from rebuild_cache_access:2.0.0. Use the rebuild_cache_access.link_lazy_builder service instead. See https://www.drupal.org/project/rebuild_cache_access/issues/3540172', E_USER_DEPRECATED);
    return \Drupal::service('rebuild_cache_access.link_lazy_builder')->renderNavigationLink($label);
  }

}
