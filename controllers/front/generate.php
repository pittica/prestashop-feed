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
 * Generator controller.
 *
 * @category Controller
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/blob/main/controllers/front/download.php
 * @since    1.0.0
 */
class PitticafeedGenerateModuleFrontController extends ModuleFrontController
{
    /**
     * {@inheritDoc}
     *
     * @return void
     * @since  1.0.0
     */
    public function initContent() : void
    {
        parent::initContent();

        $generator = $this
            ->get('pittica.prestashop.module.feed.tools.generator');
        $updater   = $this
            ->get('pittica.prestashop.module.feed.tools.updater');

        if (Tools::getValue('token') === $generator->getToken()) {
            $shop = (int) Tools::getValue('id_shop');

            $updater->generate((bool) Tools::getValue('refresh', true), Tools::getValue('provider'), $shop > 0 ? $shop : null);
        }

        die();
    }
}
