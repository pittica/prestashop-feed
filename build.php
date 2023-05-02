<?php
/**
 * PrestaShop Module - pitticafeed
 *
 * Copyright 2022 Pittica S.r.l.
 *
 * @category  Module
 * @package   Pittica\PrestaShop\Module\Feed
 * @author    Lucio Benini <info@pittica.com>
 * @copyright 2022 Pittica S.r.l.
 * @license   http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link      https://github.com/pittica/prestashop-feed
 */

function copyFiles() : void
{
    cleanOutput();
    cleanTempDir();

    $destination = "pitticafeed";
    $iterator    = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator("./", \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST
    );

    mkdir($destination, 0755);

    foreach ($iterator as $item) {
        if (!isIgnored($iterator->getPathname())) {
            if ($item->isDir() && $item->getFilename() !== $destination) {
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            }
        }
    }
}

function cleanOutput() : void
{
    if (file_exists("./pitticafeed.zip")) {
        unlink("./pitticafeed.zip");
    }
}

function cleanTempDir() : void
{
    if (file_exists("./pitticafeed")) {
        if (substr(strtoupper(PHP_OS), 0, 3) === 'WIN') {
            shell_exec('rd /s /q .\\pitticafeed');
        } else {
            shell_exec('rm -rf ./pitticafeed');
        }
    }
}

function isIgnored(string $path) : bool
{
    $path   = str_replace(DIRECTORY_SEPARATOR, '/', $path);
    $values = [
        "./build.php",
        "*.code-workspace",
        "./config_*.xml",
        "./yaml",
        "./output/*.xml",
        "./output/*.csv",
        "*.git*",
    ];

    foreach ($values as $value) {
        if (fnmatch($value, $path)) {
            return true;
        }
    }

    return false;
}

if (strtoupper(PHP_SAPI) === 'CLI') {
    if (!empty($argv[1]) && strtoupper($argv[1]) === 'CLEAR') {
        cleanTempDir();
    } else {
        copyFiles();
    }
}
