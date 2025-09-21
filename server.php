<?php
// Simple PHP server to handle static files and PHP processing
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove query parameters
$path = strtok($path, '?');

// Handle root path
if ($path === '/' || $path === '') {
    $path = '/index.html';
}

// Get the file path relative to current directory
$filePath = __DIR__ . $path;

// Handle PHP files
if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
    // Set content type for PHP files
    header('Content-Type: text/html; charset=utf-8');
    include $filePath;
    return;
}

// Handle static files
if (file_exists($filePath) && is_file($filePath)) {
    // Set appropriate content type based on file extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'html' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'ttf' => 'font/ttf',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'json' => 'application/json'
    ];
    
    $contentType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
    header('Content-Type: ' . $contentType);
    
    // Set cache control headers to prevent caching issues in Replit
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    readfile($filePath);
    return;
}

// 404 for non-existent files
http_response_code(404);
include __DIR__ . '/404_page.html';
?>