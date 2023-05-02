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

declare(strict_types=1);

namespace Pittica\PrestaShop\Module\Feed\Tool;

/**
 * Locator.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Locator.php
 * @since    1.0.0
 */
class Locator extends Tool
{
    /**
     * Gets the file path of the feeds.
     *
     * @param string       $provider  Filename.
     * @param integer|null $shopId    Shop ID.
     * @param string       $extension File extension.
     *
     * @return string
     * @since  1.0.0
     */
    public function getFilePath(string $provider, ?int $shopId = null, string $extension = 'xml') : string
    {
        return _PS_MODULE_DIR_ . 'pitticafeed' . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . ($shopId === null ? $this->getService('prestashop.adapter.legacy.context')->getContext()->shop->id : (int) $shopId) . '_' . $provider . '.' . $extension;
    }

    /**
     * Gets the download URL.
     *
     * @param string $provider Provider.
     *
     * @return string
     * @since  1.0.0
     */
    public function getDownloadUrl(string $provider) : string
    {
        return $this
            ->getService('prestashop.adapter.legacy.context')
            ->getContext()
            ->link
            ->getModuleLink(
                'pitticafeed',
                'download',
                [
                    'provider' => $provider,
                    'token'    => $this
                        ->getContainer()
                        ->get('pittica.prestashop.module.feed.tools.generator')
                        ->getToken()
                ]
            );
    }

    /**
     * Gets the generator URL.
     *
     * @param string|null $provider Provider.
     *
     * @return string
     * @since  1.0.0
     */
    public function getGeneratorUrl(?string $provider = null) : string
    {
        return $this
            ->getService('prestashop.adapter.legacy.context')
            ->getContext()
            ->link
            ->getModuleLink(
                'pitticafeed',
                'generate',
                [
                    'provider' => $provider,
                    'token'    => $this
                        ->getContainer()
                        ->get('pittica.prestashop.module.feed.tools.generator')
                        ->getToken()
                ]
            );
    }

    /**
     * Gets the CLI command.
     *
     * @param string|null $provider Provider.
     * @param string      $action   Command action.
     *
     * @return string
     * @since  1.0.0
     */
    public function getCli(?string $provider = null, string $action = 'update') : string
    {
        $cli = 'bin/console feed:' . $action;

        if (!empty($provider)) {
            return $cli . ' ' . $provider;
        } else {
            return $cli;
        }
    }
}
