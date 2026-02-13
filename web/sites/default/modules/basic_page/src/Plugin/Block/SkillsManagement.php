<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Traits\StepDataTrait;

/**
 * Provides a 'SkillsManagementRender' block.
 *
 * @Block(
 *  id = "skills_management_render",
 *  admin_label = @Translation("Skills Management Render"),
 * )
 */
class SkillsManagement extends BlockBase
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
        $build['#theme'] = 'skills_management_render';
        $build['#data'] = $data;
        return $build;
    }
}
