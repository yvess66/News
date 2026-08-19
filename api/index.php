<?php

// Paksa Laravel menggunakan folder /tmp untuk cache & logs di Vercel
$_ENV['APP_STORAGE'] = '/tmp/storage';
putenv('APP_STORAGE=/tmp/storage');

$_ENV['LOG_CHANNEL'] = 'stderr';
putenv('LOG_CHANNEL=stderr');

$runtimeDefaults = [
    'CACHE_STORE' => 'database',
    'SESSION_DRIVER' => 'database',
    'QUEUE_CONNECTION' => 'database',
    'FILESYSTEM_DISK' => 'local',
    'MAIL_MAILER' => 'log',
    'BROADCAST_CONNECTION' => 'log',
    'AUTH_GUARD' => 'web',
    'APP_MAINTENANCE_DRIVER' => 'file',
    'APP_MAINTENANCE_STORE' => 'database',
    'QUEUE_FAILED_DRIVER' => 'database-uuids',
];

foreach ($runtimeDefaults as $key => $value) {
    if (getenv($key) === false || getenv($key) === '') {
        $_ENV[$key] = $value;
        putenv($key.'='.$value);
    }
}

$_ENV['APP_BOOTSTRAP'] = '/tmp/bootstrap';
putenv('APP_BOOTSTRAP=/tmp/bootstrap');

// Buat struktur folder di dalam /tmp jika belum ada
$storageDirs = [
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';