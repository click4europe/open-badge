<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\BloccoHomeTrait;

/**
 * Provides a 'VantaggiOpenBadge' block.
 *
 * @Block(
 *  id = "vantaggi_open_badge",
 *  admin_label = @Translation("Vantaggi Open Badge"),
 * )
 */
class VantaggiOpenBadge extends BlockBase
{
    use BloccoHomeTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        $account = \Drupal::currentUser();
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // Fetch multiple blocco_home blocks using trait
        $blocks_map = [
            'hero' => 'Vantaggi Hero',
            'info_section' => 'Informazioni Open Badge',
        ];
        $data = array_merge($data, $this->fetchMultipleBloccoHome($blocks_map, $account));

        // Fetch benefit cards (blocco_vantaggi)
        $data['benefits'] = Blocchi::make_query_blocchi('blocco_vantaggi', $lang, TRUE, 'ASC', 0, 12);
        
        // Add edit link for benefits (admin only)
        if ($account->id() == 1) {
            $options = ['absolute' => TRUE, 'query' => ['type' => 'blocco_vantaggi'], 'attributes' => ['class' => ['button text-slate-900 underline']]];
            $url = Url::fromUri('internal:/admin/content/block', $options);
            $data['benefits_edit'] = Link::fromTextAndUrl('Configura Vantaggi', $url);
        }

        // Render the template
        $build = [];
        $build['#theme'] = 'vantaggi_open_badge_render';
        $build['#data'] = $data;
        return $build;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [];
    }
}
