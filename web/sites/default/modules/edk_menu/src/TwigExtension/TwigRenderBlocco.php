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
            new TwigFunction('get_block_by_type', [$this, 'get_block_by_type']),
            new TwigFunction('get_features_section', [$this, 'get_features_section']),
            new TwigFunction('get_cta_section', [$this, 'get_cta_section']),
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

        // Get field_body (description)
        if ($block->hasField('body') && !$block->get('body')->isEmpty()) {
            $value = $block->get('body')->getValue();
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
       /* if ($block->hasField('field_secondary_link') && !$block->get('field_secondary_link')->isEmpty()) {
            $value = $block->get('field_secondary_link')->getValue();
            $data['secondary_link_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['secondary_link_title'] = isset($value[0]['title']) ? $value[0]['title'] : '';
        }*/

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

    /**
     * Generic helper to get any block type by machine name.
     * Returns block entity and edit link if user has permission.
     *
     * @param string $block_type Machine name of the block type (e.g., 'hero', 'features_section')
     * @param int $limit Number of blocks to return (default 1)
     * @return array Block data with 'block' entity and 'edit_link'
     */
    public function get_block_by_type($block_type, $limit = 1)
    {
        $account = \Drupal::currentUser();
        $results = [];

        $query = \Drupal::entityQuery('block_content')
            ->condition('type', $block_type)
            ->condition('status', 1)
            ->sort('changed', 'DESC')
            ->range(0, $limit)
            ->accessCheck(TRUE);

        $ids = $query->execute();

        if (empty($ids)) {
            return $results;
        }

        $blocks = \Drupal\block_content\Entity\BlockContent::loadMultiple($ids);

        foreach ($blocks as $id => $block) {
            $item = [
                'id' => $id,
                'block' => $block,
            ];

            // Add edit link for admins
            if ($account->hasPermission('administer blocks')) {
                $options = [
                    'absolute' => TRUE,
                    'query' => ['destination' => \Drupal::request()->getRequestUri()],
                    'attributes' => ['class' => ['button']]
                ];
                $url = Url::fromRoute('entity.block_content.edit_form', ['block_content' => $id], $options);
                $item['edit_link'] = $url->toString();
                $item['edit_link_render'] = Link::fromTextAndUrl('Edit', $url);
            }

            $results[] = $item;
        }

        return $limit === 1 ? ($results[0] ?? []) : $results;
    }

    /**
     * Helper to extract field value from a block entity.
     *
     * @param \Drupal\block_content\Entity\BlockContent $block
     * @param string $field_name
     * @param string $property (default 'value')
     * @return mixed
     */
    protected function getFieldValue($block, $field_name, $property = 'value')
    {
        if ($block->hasField($field_name) && !$block->get($field_name)->isEmpty()) {
            $value = $block->get($field_name)->getValue();
            return isset($value[0][$property]) ? $value[0][$property] : null;
        }
        return null;
    }

    /**
     * Get features section block data.
     * Block type: 'features_section'
     * Fields: field_title, field_description, field_cta_label, field_cta_url, field_features (paragraph/multi-value)
     */
    public function get_features_section()
    {
        $result = $this->get_block_by_type('features_section', 1);
        if (empty($result)) {
            return [];
        }

        $block = $result['block'];
        $data = [
            'id' => $result['id'],
            'edit_link' => $result['edit_link'] ?? null,
        ];

        // Basic fields
        $data['title'] = $this->getFieldValue($block, 'field_title');
        $data['description'] = $this->getFieldValue($block, 'body');

        // CTA link
        if ($block->hasField('field_cta_link') && !$block->get('field_cta_link')->isEmpty()) {
            $value = $block->get('field_cta_link')->getValue();
            $data['cta_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['cta_label'] = isset($value[0]['title']) ? $value[0]['title'] : 'Learn More';
        }

        // Features items (if using paragraph or multi-value field)
        $data['items'] = [];
        if ($block->hasField('field_features') && !$block->get('field_features')->isEmpty()) {
            foreach ($block->get('field_features') as $index => $item) {
                $paragraph = $item->entity;
                if ($paragraph) {
                    $feature = [
                        'number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'title' => $this->getFieldValue($paragraph, 'field_title'),
                        'description' => $this->getFieldValue($paragraph, 'field_description'),
                    ];
                    $data['items'][] = $feature;
                }
            }
        }

        return $data;
    }

    /**
     * Get CTA section block data.
     * Block type: 'cta_section'
     * Fields: field_title, body, field_primary_link, field_secondary_link, field_background
     */
    public function get_cta_section()
    {
        $result = $this->get_block_by_type('cta_section', 1);
        if (empty($result)) {
            return [];
        }

        $block = $result['block'];
        $data = [
            'id' => $result['id'],
            'edit_link' => $result['edit_link'] ?? null,
        ];

        $data['title'] = $this->getFieldValue($block, 'field_title');
        $data['description'] = $this->getFieldValue($block, 'body');
        $data['background'] = $this->getFieldValue($block, 'field_background') ?? 'dark';

        // Primary link
        if ($block->hasField('field_primary_link') && !$block->get('field_primary_link')->isEmpty()) {
            $value = $block->get('field_primary_link')->getValue();
            $data['primary_link_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['primary_link_label'] = isset($value[0]['title']) ? $value[0]['title'] : '';
        }

        // Secondary link
        if ($block->hasField('field_secondary_link') && !$block->get('field_secondary_link')->isEmpty()) {
            $value = $block->get('field_secondary_link')->getValue();
            $data['secondary_link_url'] = isset($value[0]['uri']) ? Url::fromUri($value[0]['uri'])->toString() : '';
            $data['secondary_link_label'] = isset($value[0]['title']) ? $value[0]['title'] : '';
        }

        return $data;
    }
}
