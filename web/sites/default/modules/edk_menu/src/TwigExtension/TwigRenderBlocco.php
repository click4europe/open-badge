<?php

namespace Drupal\edk_menu\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Drupal\Core\Link;
use Drupal\Core\Url;

class TwigRenderBlocco extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'blocco_render';
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_hero_tailwind', [$this, 'get_hero_tailwind']),
        ];
    }

    /**
     * Get hero block data for Tailwind theme.
     * Queries 'hero' block type and returns the first one.
     */
    public function get_hero_tailwind()
    {
        $account = \Drupal::currentUser();
        $data = [];

        // Query for 'hero' block content type
        $query = \Drupal::entityQuery('block_content')
            ->condition('type', 'hero')
            ->condition('status', 1)
            ->sort('changed', 'DESC')
            ->range(0, 1)
            ->accessCheck(TRUE);
        
        $ids = $query->execute();
        
        if (empty($ids)) {
            return $data;
        }

        $id = reset($ids);
        $block = \Drupal\block_content\Entity\BlockContent::load($id);

        if (!$block) {
            return $data;
        }

        // Get field_title
        if ($block->hasField('field_title') && !$block->get('field_title')->isEmpty()) {
            $value = $block->get('field_title')->getValue();
            $data['title'] = isset($value[0]['value']) ? $value[0]['value'] : '';
        }

        // Get field_subtitle (description)
        if ($block->hasField('field_subtitle') && !$block->get('field_subtitle')->isEmpty()) {
            $value = $block->get('field_subtitle')->getValue();
            $data['description'] = isset($value[0]['value']) ? $value[0]['value'] : '';
        }

        // Get field_eybrow (eyebrow text)
        if ($block->hasField('field_eybrow') && !$block->get('field_eybrow')->isEmpty()) {
            $value = $block->get('field_eybrow')->getValue();
            $data['eyebrow'] = isset($value[0]['value']) ? $value[0]['value'] : '';
        }

        // Get field_primary_link
        if ($block->hasField('field_primary_link') && !$block->get('field_primary_link')->isEmpty()) {
            $value = $block->get('field_primary_link')->getValue();
            $data['primary_link_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['primary_link_title'] = isset($value[0]['title']) ? $value[0]['title'] : 'Scopri di più';
        }

        // Get field_secondary_link
        if ($block->hasField('field_secondary_link') && !$block->get('field_secondary_link')->isEmpty()) {
            $value = $block->get('field_secondary_link')->getValue();
            $data['secondary_link_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['secondary_link_title'] = isset($value[0]['title']) ? $value[0]['title'] : '';
        }

        // Get field_illustration (image)
        if ($block->hasField('field_illustration') && !$block->get('field_illustration')->isEmpty()) {
            $image = $block->get('field_illustration')->entity;
            if ($image) {
                $data['image_url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($image->getFileUri());
            }
        }

        $data['id'] = $id;

        // Add edit link for admins
        if ($account->hasPermission('administer blocks')) {
            $options = ['absolute' => TRUE, 'query' => ['destination' => \Drupal::request()->getRequestUri(), 'type' => 'hero'], 'attributes' => ['class' => ['button']]];
            $url = Url::fromRoute('entity.block_content.edit_form', ['block_content' => $id], $options);
            $data['link_edit'] = Link::fromTextAndUrl('Configura Hero', $url);
        }

        return $data;
    }
}
