<?php

namespace Drupal\rebuild_cache_access;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Toolbar integration handler.
 */
class ToolbarHandler implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * ToolbarHandler constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user.
   */
  public function __construct(AccountProxyInterface $account) {
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new self(
      $container->get('current_user')
    );
  }

  /**
   * Hook bridge.
   *
   * @return array
   *   The devel toolbar items render array.
   *
   * @see hook_toolbar()
   */
  public function toolbar() {

    $items['rebuild_cache_access'] = [
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];

    if ($this->account->hasPermission('rebuild cache access')) {
      $items['rebuild_cache_access'] += [
        '#type' => 'toolbar_item',
        '#weight' => 999,
        'tab' => [
          'link' => [
            '#lazy_builder' => ['rebuild_cache_access.link_lazy_builder:renderToolbarLink', []],
            '#create_placeholder' => TRUE,
          ],
        ],
        '#attached' => [
          'library' => 'rebuild_cache_access/rebuild-cache-access-toolbar',
        ],
      ];
    }

    return $items;
  }

}
