<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\BloccoHomeTrait;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'TermsRender' block.
 *
 * @Block(
 *  id = "terms_render",
 *  admin_label = @Translation("Terms Render"),
 * )
 */
class Terms extends BlockBase
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

        // Fetch hero section data using trait (page_id = 56)
        $data = $this->getStepData($lang, 56);
        
        // Fetch terms block content (Terms Block - type: blocco_home)
        $data['terms_block'] = $this->fetchBloccoHome('Terms Block', $account);

        $build = [];
        $build['#theme'] = 'terms_render';
        $build['#data'] = $data;
        return $build;
    }
}
