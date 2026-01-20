<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'ScuoleECentriDiFormazioneRender' block.
 *
 * @Block(
 *  id = "scuole_e_centri_di_formazione_render",
 *  admin_label = @Translation("Scuole e Centri di Formazione Render"),
 * )
 */
class ScuoleECentriDiFormazione extends BlockBase
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
        $build['#theme'] = 'scuole_e_centri_di_formazione_render';
        $build['#data'] = $data;
        return $build;
    }
}
