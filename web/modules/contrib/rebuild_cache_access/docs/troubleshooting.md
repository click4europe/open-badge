# Troubleshooting

## I granted the permission but see no button

- **No admin navigation module.** The button needs the core Navigation or Toolbar module. The
  module does not enable either of them. Enable one at **Extend** (`/admin/modules`). See
  [Requirements](installation.md#requirements).
- **Missing navigation permission.** The role also needs **Access navigation bar** (Navigation sidebar)
  or **Use the toolbar** (classic Toolbar). See
  [Configuration](configuration.md#users-also-need-access-to-the-admin-navigation).
- **Navigation block removed.** On Navigation sites, check the block is in the layout at
  **Configuration > User interface > Navigation blocks**. See
  [Placing the Navigation block by hand](configuration.md#placing-the-navigation-block-by-hand).
- **Block not added after a config import.** When Navigation is enabled by
  `drush config:import`, the module does not place the block; the imported
  `navigation.block_layout` wins. Place the block on one environment and export the configuration.

## The Rebuild cache link shows up twice in the Navigation sidebar

Older versions could add the block to the Navigation layout a second time, for example when the
module was uninstalled and installed again, or installed together with Navigation. Current
versions do not add a second block, but updating does not remove one that already exists.

Uninstalling the module removes all copies of the block, and installing it again adds one. This
also removes the **Rebuild Cache** permission from all roles and resets the block label and
position, so grant the permission again afterwards. On sites deployed with configuration
management, export the configuration afterwards. Alternatively, remove the extra block by hand:

1. Go to **Configuration > User interface > Navigation blocks**
   (`/admin/config/user-interface/navigation-block`).
2. Click **Enable edit mode**.
3. Hover over one of the two **Rebuild cache** blocks, open its menu, and click **Remove block**.
4. Click **Save**.

On sites deployed with configuration management, export the configuration afterwards.

## I see a toolbar button and no Navigation link, or the other way round

The module shows the button in whichever admin navigation is active. The Navigation module
replaces the classic Toolbar when enabled, so only one of the two is visible at a time.

## The status report says "Toolbar and Navigation modules are both installed"

Toolbar can stay enabled next to Navigation for several reasons: the install profile enabled it,
another module depends on it, or older versions of this module required it. This module no longer
has a dependency on the Toolbar module, but needs either Toolbar or Navigation to show the
Rebuild cache button. Update the module, then uninstall Toolbar if nothing else needs it. Other
modules such as Admin Toolbar or Gin Toolbar may depend on it, and roles lose the
`access toolbar` permission. See
[Toolbar is no longer required](installation.md#toolbar-is-no-longer-required).

## Opening the rebuild URL directly gives "Access denied"

The rebuild URL, `/rebuild-cache-access/rebuild-cache`, requires a one-time security token that
Drupal adds to the link in the navigation. Typing or bookmarking the URL without the token returns
403. This protects users from being tricked into clearing caches by a link on another site. Use
the button instead.

## The site is slow after clicking the button

That is expected. A full rebuild empties every cache, and the first requests afterwards rebuild
them. The site returns to normal speed once the caches are warm. If this happens often, limit the
permission to fewer roles.

## Clearing caches did not fix the problem

The button clears Drupal's caches only. It does not clear:

- a reverse proxy or CDN cache such as Varnish, Cloudflare, or Fastly
- the browser cache (try a hard reload or a private window)
- OPcache for PHP code changes on some hosting setups

If the stale content comes from one of these, clear it there.
