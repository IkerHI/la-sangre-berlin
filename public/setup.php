<?php
/**
 * setup.php  —  One-shot post-deploy setup.
 * Runs: migrate, storage symlink, config/route/view cache, admin seeder.
 * DELETE this file immediately after all steps show OK.
 */

$token  = $_GET['token'] ?? '';
$secret = 'lsb-setup-2026';

if (!hash_equals($secret, $token)) {
    http_response_code(403);
    die('Forbidden. Pass ?token=lsb-setup-2026');
}

// Split-directory layout:
//   /html/          = web root  (this file lives here)
//   /data/laravel/  = app root
$webRoot = __DIR__;
$appRoot = dirname(__DIR__) . '/data/laravel';
$artisan = $appRoot . '/artisan';

echo "<pre>\n";
echo "=== La Sangre Berlín — Post-deploy Setup ===\n\n";
echo "Web root : $webRoot\n";
echo "App root : $appRoot\n\n";
flush();

// ── artisan helper ────────────────────────────────────────────────────────────
function artisan(string $label, string $cmd): void {
    global $artisan;
    echo "[ ] $label...\n";
    flush();
    $result = shell_exec("php " . escapeshellarg($artisan) . " $cmd 2>&1");
    echo "    " . trim(str_replace("\n", "\n    ", trim((string)$result))) . "\n";
    echo ($result !== null ? "[OK]" : "[ERR]") . " $label\n\n";
    flush();
}

// ── 1. DB connection ──────────────────────────────────────────────────────────
echo "--- 1. Database connection ---\n";
$env = parse_ini_file("$appRoot/.env");
if ($env) {
    try {
        $dsn = "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']}";
        $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "[OK] Connected to {$env['DB_DATABASE']} @ {$env['DB_HOST']}\n\n";
    } catch (Exception $e) {
        echo "[FAIL] " . $e->getMessage() . "\n\n";
    }
} else {
    echo "[FAIL] Could not read .env at $appRoot/.env\n\n";
}
flush();

// ── 2. Migrations ─────────────────────────────────────────────────────────────
echo "--- 2. Migrations ---\n";
artisan('migrate', 'migrate --force');

// ── 3. Admin seeder ───────────────────────────────────────────────────────────
echo "--- 3. Admin seeder ---\n";
artisan('AdminSeeder', 'db:seed --class=AdminSeeder --force');

// ── 4. Storage symlink ────────────────────────────────────────────────────────
echo "--- 4. Storage symlink ---\n";
$link   = "$webRoot/storage";
$target = "$appRoot/storage/app/public";

if (is_link($link)) {
    echo "[OK] Symlink already exists -> " . readlink($link) . "\n\n";
} elseif (is_dir($link)) {
    // Directory exists (not a symlink) — remove it if empty, then create symlink
    $contents = array_diff(scandir($link), ['.', '..']);
    if (count($contents) === 0) {
        rmdir($link);
        echo (symlink($target, $link) ? "[OK]" : "[FAIL]") . " Removed empty dir, symlink created: $link -> $target\n\n";
    } else {
        // Not empty — move contents then create symlink
        echo "[INFO] Directory not empty, moving files...\n";
        foreach ($contents as $f) {
            rename("$link/$f", "$target/$f");
        }
        rmdir($link);
        echo (symlink($target, $link) ? "[OK]" : "[FAIL]") . " Files moved, symlink created: $link -> $target\n\n";
    }
} else {
    echo (symlink($target, $link) ? "[OK]" : "[FAIL]") . " symlink $link -> $target\n\n";
}
flush();

// ── 5. Laravel caches ─────────────────────────────────────────────────────────
echo "--- 5. Caches ---\n";
artisan('Config cache',  'config:cache');
artisan('Route cache',   'route:cache');
artisan('View cache',    'view:cache');

echo "=== Done ===\n";
echo "DELETE this file now: unlink / rm $webRoot/setup.php\n";
echo "</pre>\n";
