<?php

namespace Drupal\basic_page\Utils;

use Drupal\file\Entity\File;
use Drupal\user\Entity\User;

class CasiStudio
{
    /**
     * Convert page number to start offset
     */
    public static function page_to_start($page = 1, $righe = 9)
    {
        $start = 0;
        if (strcmp($page, 1) !== 0 && intval($page) > 1) {
            $start = ((intval($page) - 1) * $righe);
        }
        return $start;
    }

    /**
     * Get case studies list with optional search
     */
    public static function casi_studio_list($order = 'DESC', $start = 0, $end = 9, $title = '')
    {
        $data = array();
        $database = \Drupal::database();
        $query = \Drupal::entityQuery('node');
        $query->accessCheck(FALSE);
        $query->condition('status', 1);
        $query->condition('type', 'casi_studio');

        if (!empty($title) && strlen($title)) {
            $group = $query->orConditionGroup();
            $group->condition('title', $database->escapeLike($title), 'CONTAINS');
            $group->condition('body', $database->escapeLike($title), 'CONTAINS');
            $query->condition($group);
        }

        $num_rows = clone $query;
        $data['num'] = $num_rows->count()->execute();
        $query->range($start, $end);

        $query->sort('created', $order);
        $result = $query->execute();

        foreach ($result as $value) {
            $nodo = \Drupal\node\Entity\Node::load($value);
            $data['row'][] = self::document($nodo);
        }

        return $data;
    }

    /**
     * Extracts and formats node data for a caso studio node
     */
    public static function document($node = NULL) {
        if (!$node) {
            return [];
        }

        $data = [];
        $data['id'] = $node->id();
        $data['uid'] = $node->getOwnerId();
        $owner = User::load($node->getOwnerId());
        $data['uid_name'] = $owner ? $owner->get('name')->value : '';

        $data['title'] = $node->getTitle();
        $data['status'] = $node->isPublished();
        $data['bundle'] = $node->getType();

        $created = $node->getCreatedTime();
        $data['created'] = \Drupal::service('date.formatter')->format($created, 'short');

        $changed = $node->getChangedTime();
        $data['changed'] = \Drupal::service('date.formatter')->format($changed, 'short');

        $options = ['absolute' => TRUE];
        $data['url_object'] = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node->id()], $options);

        // field_titolo (plain text)
        if ($node->hasField('field_titolo') && !$node->get('field_titolo')->isEmpty()) {
            $data['field_titolo'] = $node->get('field_titolo')->value;
        }

        // field_organizzazione (plain text)
        if ($node->hasField('field_organizzazione') && !$node->get('field_organizzazione')->isEmpty()) {
            $data['organizzazione'] = $node->get('field_organizzazione')->value;
        }

        // field_descrizione_breve (plain text - used as card teaser)
        if ($node->hasField('field_descrizione_breve') && !$node->get('field_descrizione_breve')->isEmpty()) {
            $data['descrizione_breve'] = $node->get('field_descrizione_breve')->value;
        }

        // field_descrizione (formatted long)
        if ($node->hasField('field_descrizione') && !$node->get('field_descrizione')->isEmpty()) {
            $data['descrizione'] = $node->get('field_descrizione')->value;
        }

        // Handle body field and generate teaser
        if ($node->hasField('body') && !$node->get('body')->isEmpty()) {
            $data['body'] = $node->get('body')->value;
            $body = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $node->get('body')->value);
            $data['teaser'] = \Drupal\Component\Utility\Unicode::truncate(strip_tags($body), 150, TRUE, TRUE, 2);
        }

        // Handle image field (check common field names)
        $data['image'] = [];
        $image_field = null;

        if ($node->hasField('field_immagine') && !$node->get('field_immagine')->isEmpty()) {
            $image_field = 'field_immagine';
        } elseif ($node->hasField('field_image') && !$node->get('field_image')->isEmpty()) {
            $image_field = 'field_image';
        }

        if ($image_field) {
            $fid = $node->get($image_field)->target_id;
            $file = File::load($fid);
            if ($file) {
                $path = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
                $data['image']['path'] = $path;
                $data['image']['alt'] = $node->get($image_field)->alt ?: '';
                $data['image']['title'] = $node->get($image_field)->title ?: '';
            }
        }

        return $data;
    }

    /**
     * Get all path segments
     */
    public static function all_get_path() {
        $current_path = \Drupal::service('path.current')->getPath();
        return $current_path;
    }

    /**
     * Get request parameter
     */
    public static function get_request($key) {
        return \Drupal::request()->get($key);
    }

    /**
     * Generate pagination HTML
     */
    public static function pager($current, $total, $limit, $path, $args = []) {
        $pages = ceil($total / $limit);
        if ($pages <= 1) return '';

        $html = '<nav class="flex items-center justify-center" aria-label="Pagination">';
        $html .= '<div class="flex items-center space-x-1">';

        // Previous
        if ($current > 1) {
            $prevArgs = $args;
            $prevArgs['page'] = $current - 1;
            $html .= '<a href="' . $path . '?' . http_build_query($prevArgs) . '" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-900 border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-900 transition-all duration-200">';
            $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>';
            $html .= '</svg>';
            $html .= '</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 border border-slate-200 rounded-lg cursor-not-allowed">';
            $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>';
            $html .= '</svg>';
            $html .= '</span>';
        }

        // Page numbers
        $start = max(1, $current - 2);
        $end = min($pages, $current + 2);

        if ($start > 1) {
            $firstArgs = $args;
            $firstArgs['page'] = 1;
            $html .= '<a href="' . $path . '?' . http_build_query($firstArgs) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-900 border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-900 transition-all duration-200">1</a>';
            if ($start > 2) {
                $html .= '<span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 border border-slate-200 rounded-lg">...</span>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $pageArgs = $args;
            $pageArgs['page'] = $i;
            if ($i == $current) {
                $html .= '<span aria-current="page" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-900 border-2 border-slate-900 rounded-lg bg-slate-50">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $path . '?' . http_build_query($pageArgs) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-900 border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-900 transition-all duration-200">' . $i . '</a>';
            }
        }

        if ($end < $pages) {
            if ($end < $pages - 1) {
                $html .= '<span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 border border-slate-200 rounded-lg">...</span>';
            }
            $lastArgs = $args;
            $lastArgs['page'] = $pages;
            $html .= '<a href="' . $path . '?' . http_build_query($lastArgs) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-900 border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-900 transition-all duration-200">' . $pages . '</a>';
        }

        // Next
        if ($current < $pages) {
            $nextArgs = $args;
            $nextArgs['page'] = $current + 1;
            $html .= '<a href="' . $path . '?' . http_build_query($nextArgs) . '" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-900 border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-900 transition-all duration-200">';
            $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
            $html .= '</svg>';
            $html .= '</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-300 border border-slate-200 rounded-lg cursor-not-allowed">';
            $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
            $html .= '</svg>';
            $html .= '</span>';
        }

        $html .= '</div>';
        $html .= '</nav>';
        return $html;
    }

    /**
     * Redirect to URL
     */
    public static function drupal_goto($path) {
        return new \Symfony\Component\HttpFoundation\RedirectResponse($path);
    }
}
