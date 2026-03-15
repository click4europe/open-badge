<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'UpskillingRender' block.
 *
 * @Block(
 *  id = "upskilling_render",
 *  admin_label = @Translation("Upskilling Render"),
 * )
 */
class Upskilling extends BlockBase
{
    use StepDataTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $account = \Drupal::currentUser();
        $data = $this->getStepData($lang, 6);

        $upskilling = $this->fetchBloccoHome('Upskilling', $account);
        if ($upskilling) {
            $data['blocco_home'] = $upskilling;
        }
        $build = [];
        $build['#theme'] = 'upskilling_render';
        $build['#data'] = $data;
        return $build;
    }
}
