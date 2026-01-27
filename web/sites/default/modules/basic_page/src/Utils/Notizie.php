<?php

namespace Drupal\basic_page\Utils;

use Drupal\file\Entity\File;
use Drupal\user\Entity\User;

class Notizie
{
    /**
     * Convert page number to start offset
     */
    public static function page_to_start($page = 1, $righe = 10)
    {
        $start = 0;
        if (strcmp($page, 1) !== 0 && intval($page) > 1) {
            $start = ((intval($page) - 1) * $righe);
        }
        return $start;
    }

    /**
     * Return allowed categories for news
     */
    public static function field_allowed_categories()
    {
        // Return empty array since we're not using categories
        return [];
    }

    /**
     * Get news list with search only (no categories)
     */
    public static function notizie_list($promote = 0, $order = 'DESC', $start = 0, $end = 10, $tid = '', $title = '')
    {
        $data = array();
        $database = \Drupal::database();
        $query = \Drupal::entityQuery('node');
        $query->accessCheck(FALSE);
        $query->condition('status', 1);
        $query->condition('type', 'notizie');

        // No category filter as requested
        // if (!empty($tid) && strlen($tid)) {
        //     $query->condition('field_categoria_notizia', $tid, '=');
        // }

        if (!empty($title) && strlen($title)) {
            $group = $query->orConditionGroup();
            $group->condition('title', $database->escapeLike($title), 'CONTAINS');
            $group->condition('body', $database->escapeLike($title), 'CONTAINS');
            $query->condition($group);
        }

        if (!empty($promote) && strlen($promote) && strcmp($promote, '1') === 0) {
            $query->condition('promote', $promote, '=');
        }

        $num_rows = clone $query;
        $data['num'] = $num_rows->count()->execute();
        $query->range($start, $end);

        $query->sort('created', $order);
        $result = $query->execute();

        // Load the data
        foreach ($result as $value) {
            $nodo = \Drupal\node\Entity\Node::load($value);
            $data['row'][] = self::document($nodo);
        }

        return $data;
    }

    /**
     * Get a single news item by alias (eyca approach)
     */
    public static function get_notizia($url = '')
    {
        $database = \Drupal::database();
        $programe = [];
        $alias = $url;

        $path = $database->query("SELECT path FROM {path_alias} where alias = :alias", [':alias' => $alias])->fetchObject();

        if (empty($path) && !is_object($path)) {
            return false;
        }

        if (preg_match('{\/node\/(\d+)}', $path->path, $match)) {
            $aliased_nids = $match[1];
        }
        
        if (is_numeric($aliased_nids)) {
            $nodo = \Drupal\node\Entity\Node::load($aliased_nids);
            switch ($nodo->getType()) {
                case 'notizie':
                    $doc = self::document($nodo);
                    break;
                default:
                    $doc = NULL;
            }
            array_push($programe, $doc);
        }

        return $doc;
    }

    /**
     * Extracts and formats node data (from Page.php)
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
        
        // Handle image field (check field_immagine, field_image, and media fields)
        $data['image'] = [];
        $image_field = null;
        
        // Check for direct image fields
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
        
        // Check for media reference field if no direct image found
        if (empty($data['image']['path'])) {
            $media_fields = ['field_media_image', 'field_media', 'field_featured_image'];
            foreach ($media_fields as $media_field) {
                if ($node->hasField($media_field) && !$node->get($media_field)->isEmpty()) {
                    $media = $node->get($media_field)->entity;
                    if ($media && $media->hasField('field_media_image')) {
                        $file = $media->get('field_media_image')->entity;
                        if ($file) {
                            $path = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
                            $data['image']['path'] = $path;
                            $data['image']['alt'] = $media->get('field_media_image')->alt ?: '';
                            $data['image']['title'] = $media->getName() ?: '';
                            break;
                        }
                    }
                }
            }
        }
        
        // Handle body field and generate teaser
        if ($node->hasField('body') && !$node->get('body')->isEmpty()) {
            $data['body'] = $node->get('body')->value;
            // Generate teaser from body
            $body = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $node->get('body')->value);
            $data['teaser'] = \Drupal\Component\Utility\Unicode::truncate(strip_tags($body), 150, TRUE, TRUE, 2);
        }
        
        // Handle subtitle field (check both field_sotto_titolo and field_subtitle)
        if ($node->hasField('field_sotto_titolo') && !$node->get('field_sotto_titolo')->isEmpty()) {
            $data['sotto_titolo'] = $node->get('field_sotto_titolo')->getValue();
        } elseif ($node->hasField('field_subtitle') && !$node->get('field_subtitle')->isEmpty()) {
            $data['sotto_titolo'][0]['value'] = $node->get('field_subtitle')->value;
        }
        
        // Handle FAQ field
        if ($node->hasField('field_faq') && !$node->get('field_faq')->isEmpty()) {
            $data['field_faq'] = [];
            foreach ($node->get('field_faq') as $item) {
                if ($item->entity) {
                    $data['field_faq'][] = [
                        'question' => $item->entity->get('field_question')->value,
                        'answer' => $item->entity->get('field_answer')->value,
                    ];
                }
            }
        }
        
        // Handle custom blocks field
        if ($node->hasField('field_custom_blocks') && !$node->get('field_custom_blocks')->isEmpty()) {
            $data['personalizzati'] = $node->get('field_custom_blocks')->getValue();
        }
        
        // Handle webform field
        if ($node->hasField('field_webform') && !$node->get('field_webform')->isEmpty()) {
            $webform = $node->get('field_webform')->entity;
            if ($webform) {
                $data['webform_id'] = $webform->id();
                $data['webform_status'] = $webform->status();
            }
        }
        
        return $data;
    }

    /**
     * Load user by ID or current user (from Utils.php)
     */
    public static function user_load($uid = NULL) {
        if ($uid === NULL) {
            $uid = \Drupal::currentUser()->id();
        }
        return User::load($uid);
    }
    
    /**
     * Get all path segments (from Utils.php)
     */
    public static function all_get_path() {
        $current_path = \Drupal::service('path.current')->getPath();
        return $current_path;
    }
    
    /**
     * Get request parameter (from Utils.php)
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
     * Redirect to URL (from Utils.php)
     */
    public static function drupal_goto($path) {
        return new \Symfony\Component\HttpFoundation\RedirectResponse($path);
    }
    
    /**
     * Get URL argument by position (from Utils.php)
     */
    public static function arg($index = 0) {
        $current_path = \Drupal::service('path.current')->getPath();
        $args = explode('/', trim($current_path, '/'));
        return isset($args[$index]) ? $args[$index] : '';
    }
}