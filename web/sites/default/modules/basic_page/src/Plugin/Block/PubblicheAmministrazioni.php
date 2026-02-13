<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'PubblicheAmministrazioniRender' block.
 *
 * @Block(
 *  id = "pubbliche_amministrazioni_render",
 *  admin_label = @Translation("Pubbliche Amministrazioni Render"),
 * )
 */
class PubblicheAmministrazioni extends BlockBase
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
        $build['#theme'] = 'pubbliche_amministrazioni_render';
        $build['#data'] = $data;
        return $build;
    }
}
