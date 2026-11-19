<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'AbbonamentiTestRender' block.
 *
 * @Block(
 *  id = "abbonamenti_test_render",
 *  admin_label = @Translation("Abbonamenti Test Render"),
 * )
 */
class AbbonamentiTest extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $build = [];
        $build['#theme'] = 'abbonamenti_test_render';
        $build['#attached']['library'][] = 'basic_page/basic_page.abbonamenti_test';
        return $build;
    }
}
