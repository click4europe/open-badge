<?php
/**
 * Performance test script for Drupal
 * Run this script to measure page load times and database queries
 */

// Bootstrap Drupal
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'web/autoload.php';
$request = Request::createFromGlobals();
DrupalKernel::createFromRequest($request, $autoloader, 'prod')->boot();

// Start timing
$start_time = microtime(true);
$start_memory = memory_get_usage();

// Test basic page render
try {
    // Simulate a basic page request
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    
    // Test 1: Block query performance
    echo "=== Testing Block Query Performance ===\n";
    $block_start = microtime(true);
    
    $database = \Drupal::service('database');
    $query = $database->select('block_content', 'b');
    $query->condition('b.type', 'blocco_vantaggi');
    $query->condition('b.langcode', 'en-gb');
    $query->fields('b', ['id', 'type', 'langcode']);
    $query->range(0, 3);
    
    $blocks = $query->execute()->fetchAll();
    $block_time = microtime(true) - $block_start;
    
    echo "Block query time: " . round($block_time * 1000, 2) . "ms\n";
    echo "Blocks found: " . count($blocks) . "\n\n";
    
    // Test 2: Node query performance
    echo "=== Testing Node Query Performance ===\n";
    $node_start = microtime(true);
    
    $query = \Drupal::entityQuery('node')
        ->condition('type', 'notizie')
        ->condition('status', 1)
        ->sort('created', 'DESC')
        ->range(0, 2)
        ->accessCheck(FALSE);
    
    $nids = $query->execute();
    $node_time = microtime(true) - $node_start;
    
    echo "Node query time: " . round($node_time * 1000, 2) . "ms\n";
    echo "Nodes found: " . count($nids) . "\n\n";
    
    // Test 3: Full page simulation
    echo "=== Testing Full Page Simulation ===\n";
    $page_start = microtime(true);
    
    // Simulate BasicPageRender queries
    $lang = 'en-gb';
    
    // Header block query
    $header_query = $database->select('block_content', 'b');
    $header_query->condition('b.type', 'blocco_header_pagina_interne');
    $header_query->condition('b.langcode', $lang);
    $header_query->fields('b', ['id', 'type', 'langcode']);
    $header_query->range(0, 1);
    $header_blocks = $header_query->execute()->fetchAll();
    
    // Footer block query
    $footer_query = $database->select('block_content', 'b');
    $footer_query->condition('b.type', 'blocco_footer');
    $footer_query->condition('b.langcode', $lang);
    $footer_query->fields('b', ['id', 'type', 'langcode']);
    $footer_query->range(0, 1);
    $footer_blocks = $footer_query->execute()->fetchAll();
    
    $page_time = microtime(true) - $page_start;
    
    echo "Full page simulation time: " . round($page_time * 1000, 2) . "ms\n";
    echo "Header blocks: " . count($header_blocks) . "\n";
    echo "Footer blocks: " . count($footer_blocks) . "\n\n";
    
    // Summary
    $total_time = microtime(true) - $start_time;
    $memory_used = memory_get_usage() - $start_memory;
    
    echo "=== Performance Summary ===\n";
    echo "Total execution time: " . round($total_time * 1000, 2) . "ms\n";
    echo "Memory used: " . round($memory_used / 1024 / 1024, 2) . "MB\n";
    echo "Peak memory: " . round(memory_get_peak_usage() / 1024 / 1024, 2) . "MB\n";
    
    // Performance recommendations
    echo "\n=== Performance Analysis ===\n";
    if ($block_time > 50) {
        echo "⚠️  Block queries are slow (>50ms)\n";
    } else {
        echo "✅ Block queries are fast (<50ms)\n";
    }
    
    if ($node_time > 100) {
        echo "⚠️  Node queries are slow (>100ms)\n";
    } else {
        echo "✅ Node queries are fast (<100ms)\n";
    }
    
    if ($page_time > 200) {
        echo "⚠️  Page simulation is slow (>200ms)\n";
    } else {
        echo "✅ Page simulation is fast (<200ms)\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
echo "1. Run this script before and after optimizations\n";
echo "2. Test with different languages if multilingual\n";
echo "3. Monitor database query counts\n";
echo "4. Use browser dev tools for frontend performance\n";
