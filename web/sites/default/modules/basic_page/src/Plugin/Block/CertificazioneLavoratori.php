<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'CertificazioneLavoratoriRender' block.
 *
 * @Block(
 *  id = "certificazione_lavoratori_render",
 *  admin_label = @Translation("Certificazione Lavoratori Render"),
 * )
 */
class CertificazioneLavoratori extends BlockBase
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
        $build['#theme'] = 'certificazione_lavoratori_render';
        $build['#data'] = $data;
        return $build;
    }
}
