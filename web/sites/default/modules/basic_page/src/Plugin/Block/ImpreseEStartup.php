<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'ImpreseEStartupRender' block.
 *
 * @Block(
 *  id = "imprese_e_startup_render",
 *  admin_label = @Translation("Imprese e Startup Render"),
 * )
 */
class ImpreseEStartup extends BlockBase
{
    use StepDataTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $data = $this->getStepData($lang, 6);

        $build = [];
        $build['#theme'] = 'imprese_e_startup_render';
        $build['#data'] = $data;
        return $build;
    }
}
