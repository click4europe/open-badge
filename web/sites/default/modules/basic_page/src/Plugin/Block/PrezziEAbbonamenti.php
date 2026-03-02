<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;

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
    use BloccoHomeTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        $account = \Drupal::currentUser();

        // Fetch the pricing section block
        $data['pricing_section'] = $this->fetchBloccoHome('prezzi-e-abbonamenti', $account);
        
        $build = [];
        $build['#theme'] = 'prezzi_e_abbonamenti_render';
        $build['#data'] = $data;
        return $build;
    }
}
