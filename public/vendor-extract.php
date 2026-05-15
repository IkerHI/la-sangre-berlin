<?php
/**
 * vendor-extract.php — One-shot: extract vendor.zip into vendor/
 * DELETE this file immediately after use.
 */

$token  = $_GET['token'] ?? '';
$secret = 'lsb-extract-2026';

if (!hash_equals($secret, $token)) {
    http_response_code(403);
    die('Forbidden. Pass ?token=lsb-extract-2026');
}

$appRoot = dirname(__DIR__) . '/data/laravel';
$zip     = $appRoot . '/vendor.zip';
$dest    = $appRoot . '/vendor';

echo "<pre>\n";
echo "App root : $appRoot\n";
echo "ZIP      : $zip\n";

if (!file_exists($zip)) {
    die("ERROR: vendor.zip not found at $zip\n");
}

$size = round(filesize($zip) / 1048576, 1);
echo "ZIP size : {$size} MB\n";
echo "Extracting...\n";
flush();

$za = new ZipArchive();
if ($za->open($zip) !== true) {
    die("ERROR: Cannot open ZIP\n");
}

if (!is_dir($dest)) {
    mkdir($dest, 0755, true);
}

$ok = $za->extractTo($dest);
$za->close();

if ($ok) {
    unlink($zip);
    echo "OK — vendor/ extracted, vendor.zip deleted.\n";
    echo "\nNext step: visit /setup.php?token=lsb-setup-2026\n";
} else {
    echo "ERROR: Extraction failed.\n";
}

echo "</pre>\n";
