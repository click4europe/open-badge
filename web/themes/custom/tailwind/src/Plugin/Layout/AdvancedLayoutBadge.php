<?php

namespace Drupal\tailwind\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;

/**
 * Advanced layout badge class.
 */
class AdvancedLayoutBadge extends LayoutDefault {
    
    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        return parent::defaultConfiguration() + [
            'extra_classes' => '',
            'container_width' => 'container',
        ];
    }
}
