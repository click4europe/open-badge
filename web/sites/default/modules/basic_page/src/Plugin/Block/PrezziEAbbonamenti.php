<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PrezziEAbbonamentiRender' block.
 *
 * @Block(
 *  id = "prezzi_e_abbonamenti_render",
 *  admin_label = @Translation("Prezzi e Abbonamenti Render"),
 * )
 */
class PrezziEAbbonamenti extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        
        $build = [];
        $build['#theme'] = 'prezzi_e_abbonamenti_render';
        $build['#data'] = $data;
        return $build;
    }
}
