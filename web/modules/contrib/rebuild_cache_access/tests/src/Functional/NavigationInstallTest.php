<?php

declare(strict_types=1);

namespace Drupal\Tests\rebuild_cache_access\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests navigation block auto-installation order for rebuild_cache_access.
 *
 * @group rebuild_cache_access
 */
#[Group('rebuild_cache_access')]
#[RunTestsInSeparateProcesses]
class NavigationInstallTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['test_page_test'];

  /**
   * Tests block auto-installs when navigation is enabled after this module.
   *
   * Simulates: rebuild_cache_access first, navigation installed later.
   */
  public function testNavigationInstalledAfterModule(): void {
    $test_page_url = Url::fromRoute('test_page_test.test_page');
    $module_installer = $this->container->get('module_installer');

    $module_installer->install(['rebuild_cache_access']);
    $module_installer->install(['navigation']);

    $user = $this->drupalCreateUser([
      'access navigation',
      'rebuild cache access',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet($test_page_url);
    $this->assertSession()->pageTextContains('Rebuild cache');
  }

  /**
   * Tests block auto-installs when this module is enabled after navigation.
   *
   * Simulates: navigation first, rebuild_cache_access installed later.
   */
  public function testModuleInstalledAfterNavigation(): void {
    $test_page_url = Url::fromRoute('test_page_test.test_page');
    $module_installer = $this->container->get('module_installer');

    $module_installer->install(['navigation']);
    $module_installer->install(['rebuild_cache_access']);

    $user = $this->drupalCreateUser([
      'access navigation',
      'rebuild cache access',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet($test_page_url);
    $this->assertSession()->pageTextContains('Rebuild cache');
  }

  /**
   * Tests the block is removed on uninstall and not duplicated on reinstall.
   */
  public function testUninstallAndReinstall(): void {
    $test_page_url = Url::fromRoute('test_page_test.test_page');
    $module_installer = $this->container->get('module_installer');

    $module_installer->install(['navigation', 'rebuild_cache_access']);
    $this->assertSame(1, $this->countNavigationBlocks());

    $module_installer->uninstall(['rebuild_cache_access']);
    $this->assertSame(0, $this->countNavigationBlocks());

    $module_installer->install(['rebuild_cache_access']);
    $this->assertSame(1, $this->countNavigationBlocks());

    // Installing again must not add a second block, e.g. via the update hook.
    $this->container->get('module_handler')->loadInclude('rebuild_cache_access', 'install');
    rebuild_cache_access_update_11001();
    $this->assertSame(1, $this->countNavigationBlocks());

    $user = $this->drupalCreateUser([
      'access navigation',
      'rebuild cache access',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet($test_page_url);
    $this->assertSession()->elementsCount('xpath', '//a[contains(@href, "/rebuild-cache-access/rebuild-cache")]', 1);
  }

  /**
   * Tests the Navigation layout is left alone when uninstalled by config sync.
   */
  public function testUninstallDuringConfigSync(): void {
    \Drupal::service('module_installer')->install(['navigation', 'rebuild_cache_access']);
    $this->assertSame(1, $this->countNavigationBlocks());

    // Use the current container, as installing modules rebuilds it.
    \Drupal::service('config.installer')->setSyncing(TRUE);
    \Drupal::service('module_installer')->uninstall(['rebuild_cache_access']);
    \Drupal::service('config.installer')->setSyncing(FALSE);
    $this->assertSame(1, $this->countNavigationBlocks());
  }

  /**
   * Counts the rebuild cache blocks in the Navigation layout.
   */
  protected function countNavigationBlocks(): int {
    $count = 0;
    $sections = $this->config('navigation.block_layout')->get('sections') ?? [];
    foreach ($sections as $section) {
      foreach ($section['components'] ?? [] as $component) {
        if (($component['configuration']['id'] ?? NULL) === 'rebuild_cache_access_navigation') {
          $count++;
        }
      }
    }
    return $count;
  }

}
