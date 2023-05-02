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

/**
 * Download controller.
 *
 * @category Controller
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/blob/main/controllers/front/download.php
 * @since    1.0.0
 */
class PitticafeedDownloadModuleFrontController extends ModuleFrontController
{
    /**
     * {@inheritDoc}
     *
     * @return void
     * @since  1.0.0
     */
    public function initContent()
    {
        parent::initContent();

        $generator = $this
            ->get('pittica.prestashop.module.feed.tools.generator');

        if (Tools::getValue('token') === $generator->getToken()) {
            $shop          = (int) Tools::getValue('id_shop');
            $provider      = Tools::getValue('provider', 'google');
            $configuration = $this
                ->get('prestashop.adapter.legacy.configuration');

            if ($configuration->getBoolean('PITTICA_FEED_' . strtoupper($provider) . '_ACTIVE')) {
                $extension = $this
                    ->get('pittica.prestashop.module.feed.providers.' . strtolower($provider))
                    ->getExtension();
                $path      = $this
                    ->get('pittica.prestashop.module.feed.tools.locator')
                    ->getFilePath($provider, $shop > 0 ? $shop : null, $extension);

                if (file_exists($path)) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                    header('Cache-Control: public');
                    header('Content-Type: text/' . $extension);
                    readfile($path);
                }
            }
        }

        die();
    }
}
