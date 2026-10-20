# Technical reference

A summary of what the module registers, for developers and site builders who manage configuration
in code.

## Permission

| Machine name | Label | Restricted |
|---|---|---|
| `rebuild cache access` | Rebuild Cache | Yes |

Grant it in code or with Drush:

```bash
drush role:perm:add content_editor 'rebuild cache access'
```

Or in a recipe:

```yaml
config:
  actions:
    user.role.content_editor:
      grantPermission: 'rebuild cache access'
```

## Route

| Route name | Path | Requirements |
|---|---|---|
| `rebuild_cache_access.rebuild_cache` | `/rebuild-cache-access/rebuild-cache` | `rebuild cache access` permission, CSRF token |

The route is marked as an admin route. The controller
`Drupal\rebuild_cache_access\Controller\RebuildCacheAccessController::rebuildCache()`:

1. Calls `views_invalidate_cache()` if Views is enabled.
2. Calls [`drupal_flush_all_caches()`](https://api.drupal.org/api/drupal/core%21includes%21common.inc/function/drupal_flush_all_caches/11.x).
3. Adds the status message **All caches cleared.**
4. Logs `All caches cleared.` at Info level to the `rebuild_cache_access` logger channel.
5. Redirects to the HTTP referer, or to `/` when there is none. A `destination` query parameter
   takes precedence; core applies it and only allows local targets.

To link to the rebuild from your own code, build the URL from the route so Drupal adds the CSRF
token, and pass the current page as `destination` so users return to it even when the browser
sends no referer:

```php
use Drupal\Core\Url;

$url = Url::fromRoute('rebuild_cache_access.rebuild_cache', [], [
  'query' => \Drupal::destination()->getAsArray(),
]);
```

## Toolbar item

The module does not have a dependency on the Toolbar module, but needs either Toolbar or
Navigation to show the Rebuild cache button. `hook_toolbar()` only runs when the core Toolbar
module is installed. It adds the `rebuild_cache_access` toolbar item at weight 999, visible only to users
with the permission. It varies by the `user.permissions` cache context.

The link itself is rendered in a placeholder by the `rebuild_cache_access.link_lazy_builder`
service. It carries the current page as `destination` and varies by `url`, without making the
cached toolbar vary by URL.

## Navigation block

| Block plugin ID | Default label |
|---|---|
| `rebuild_cache_access_navigation` | Rebuild cache |

- Allowed in the Navigation layout (`allow_in_navigation`).
- Hidden from the regular Block layout UI (`_block_ui_hidden`).
- Rendered in a placeholder by the `rebuild_cache_access.link_lazy_builder` service, varied by
  `user.permissions` and `url`. The link carries the current page as `destination`.
- Added to `navigation.block_layout` with the `addNavigationBlock` config action, when this module
  is installed with Navigation already enabled, or when Navigation is installed later. Installing
  both modules together adds it once. Skipped during config sync, on core versions without that
  config action, and when the layout already contains the block.
- Removed from `navigation.block_layout` when this module is uninstalled, except during config
  sync.

## Logger channel

`logger.channel.rebuild_cache_access`, channel name `rebuild_cache_access`.

## Help page

The module's help page at `/admin/help/rebuild_cache_access` shows the README. If the contributed
Markdown module is enabled, the README is rendered as HTML; otherwise it is shown as plain text.
