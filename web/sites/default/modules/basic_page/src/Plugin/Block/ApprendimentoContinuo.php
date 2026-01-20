<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'ApprendimentoContinuoRender' block.
 *
 * @Block(
 *  id = "apprendimento_continuo_render",
 *  admin_label = @Translation("Apprendimento Continuo Render"),
 * )
 */
class ApprendimentoContinuo extends BlockBase
{
    use StepDataTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // Fetch step data using trait
        $data = $this->getStepData($lang, 6);

        $build = [];
        $build['#theme'] = 'apprendimento_continuo_render';
        $build['#data'] = $data;
        return $build;
    }
}
