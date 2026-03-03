<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'PrivacyRender' block.
 *
 * @Block(
 *  id = "privacy_render",
 *  admin_label = @Translation("Privacy Render"),
 * )
 */
class Privacy extends BlockBase
{
    use BloccoHomeTrait;
    use StepDataTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $account = \Drupal::currentUser();

        // Fetch hero section data using trait (page_id = 54)
        $data = $this->getStepData($lang, 54);
        
        // Fetch privacy block content (Privacy Block - type: blocco_home)
        $data['privacy_block'] = $this->fetchBloccoHome('Privacy Block', $account);

        $build = [];
        $build['#theme'] = 'privacy_render';
        $build['#data'] = $data;
        return $build;
    }
}
