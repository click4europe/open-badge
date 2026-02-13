<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'DigitalCredentialingRender' block.
 *
 * @Block(
 *  id = "digital_credentialing_render",
 *  admin_label = @Translation("Digital Credentialing Render"),
 * )
 */
class DigitalCredentialing extends BlockBase
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
        $build['#theme'] = 'digital_credentialing_render';
        $build['#data'] = $data;
        return $build;
    }
}
