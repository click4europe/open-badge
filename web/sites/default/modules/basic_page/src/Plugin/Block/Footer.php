<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\blocchi\Utils\Blocchi;


/**
 * Provides a 'Footer' block.
 *
 * @Block(
 *  id = "footer_render",
 *  admin_label = @Translation("Footer render"),
 * )
 */
class Footer extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // Fetch footer block (blocco_footer)
        $footer_blocks = Blocchi::make_query_blocchi('blocco_footer', $lang, TRUE, 'ASC', 0, 1);

        $build = [];
        if (!empty($footer_blocks)) {
            $footer_id = $footer_blocks[0]->id;
            $block_content = \Drupal::entityTypeManager()
                ->getStorage('block_content')
                ->load($footer_id);

            if ($block_content) {
                $view_builder = \Drupal::entityTypeManager()
                    ->getViewBuilder('block_content');
                $build = $view_builder->view($block_content, 'full');
            }
        }

        return $build;
    }
}
