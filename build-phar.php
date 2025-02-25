<?php

declare(strict_types=1);

$pharFile = __DIR__ . '/rwatch.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

$phar = new Phar($pharFile, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'rwatch.phar');
$phar->startBuffering();

// Add project files
$rootPath = realpath(__DIR__);
$srcPath = $rootPath . '/src';
$vendorPath = $rootPath . '/vendor';
$binFile = $rootPath . '/rwatch.php';

// Add all PHP files from the src directory
$directory = new RecursiveDirectoryIterator($srcPath, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directory);
foreach ($iterator as $file) {
    $relativePath = str_replace($rootPath . '/', '', $file->getRealPath());
    $phar->addFile($file->getRealPath(), $relativePath);
}

// Add vendor files, excluding dev dependencies
$composerJson = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
$devPackages = array_keys($composerJson['require-dev'] ?? []);
$devPaths = array_map(fn($pkg) => $vendorPath . '/' . str_replace('/', '-', $pkg), $devPackages);

$directory = new RecursiveDirectoryIterator($vendorPath, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directory);
foreach ($iterator as $file) {
    $realPath = $file->getRealPath();
    if (str_ends_with($realPath, '.php') && !preg_match('#(' . implode('|', array_map('preg_quote', $devPaths)) . ')#', $realPath)) {
        $relativePath = str_replace($rootPath . '/', '', $realPath);
        echo "Adding file: " . $relativePath . PHP_EOL;
        $phar->addFile($realPath, $relativePath);
    }
}

// Add the main CLI script
$phar->addFile($binFile, 'rwatch.php');

// Set the stub
$phar->setStub(
    "#!/usr/bin/env php\n" . $phar->createDefaultStub('rwatch.php')
);

$phar->stopBuffering();

// Make it executable
chmod($pharFile, 0755);

echo "PHAR built successfully: rwatch.phar\n";
