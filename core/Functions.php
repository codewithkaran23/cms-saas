<?php
// core/Functions.php

/**
 * Get the project root URL (e.g. http://localhost/cms-saas/)
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // We want the URL to the project root, not the current folder
    // This looks for 'cms-saas' in the path
    $script_name = $_SERVER['SCRIPT_NAME'];
    $pos = strpos($script_name, '/cms-saas');
    $project_root = ($pos !== false) ? '/cms-saas' : '';
    
    return $protocol . "://" . $host . $project_root . "/" . ltrim($path, '/');
}

/**
 * Safe redirect to a project path
 */
function redirect($path) {
    header("Location: " . base_url($path));
    exit;
}

/**
 * Sanitize output
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}
