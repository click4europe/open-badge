<?php

namespace Drupal\blocchi\Utils;

class Blocchi
{
    public static function make_query_blocchi($type = '', $lang = '', $weight = FALSE, $order = 'DESC', $start = '', $end = '')
    {
        if (!strlen($lang)) {
            return FALSE;
        }

        $database = \Drupal::service('database');
        $query = $database->select('block_content', 'b');
        $query->condition('b.type', $type, '=');
        $query->condition('b.langcode', $lang, '=');

        if ($weight && $database->schema()->tableExists('block_content__field_ordine')) {
            $query->join('block_content__field_ordine', 'bfo', 'b.id = bfo.entity_id');
            $query->orderBy('bfo.field_ordine_value', $order);
        } else {
            $query->orderBy('b.id', $order);
        }

        if (!empty($end)) {
            $query->range($start, $end);
        }

        $query->fields('b');
        
        // Join body field to get tab labels / descriptions
        if ($database->schema()->tableExists('block_content__body')) {
            $query->leftJoin('block_content__body', 'bb', 'b.id = bb.entity_id');
            $query->addField('bb', 'body_value', 'body');
        }
        
        // Join field_titolo for step titles (blocco_vantaggi)
        if ($database->schema()->tableExists('block_content__field_titolo')) {
            $query->leftJoin('block_content__field_titolo', 'bt', 'b.id = bt.entity_id');
            $query->addField('bt', 'field_titolo_value', 'titolo');
        }
        
        // Join field_step_title for step titles (blocco_step)
        if ($database->schema()->tableExists('block_content__field_step_title')) {
            $query->leftJoin('block_content__field_step_title', 'bst', 'b.id = bst.entity_id');
            $query->addField('bst', 'field_step_title_value', 'field_step_title');
        }
        
        // Join field_video for video file (File field - stores target_id)
        if ($database->schema()->tableExists('block_content__field_video')) {
            $query->leftJoin('block_content__field_video', 'bv', 'b.id = bv.entity_id');
            $query->addField('bv', 'field_video_target_id', 'video_fid');
        }
        
        // Join field_step_number for step numbers
        if ($database->schema()->tableExists('block_content__field_step_number')) {
            $query->leftJoin('block_content__field_step_number', 'bsn', 'b.id = bsn.entity_id');
            $query->addField('bsn', 'field_step_number_value', 'step_number');
        }
        
        return $query->execute()->fetchAll();
    }
}