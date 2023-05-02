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
 * @link     https://github.com/pittica/prestashop-feed/blob/main/controllers/front/cron.php
 * @since    1.0.0
 */
class PitticaFeedCronModuleFrontController extends ModuleFrontController
{
    /**
     * A value indicating whether the controller needs authentication.
     *
     * @var   boolean
     * @since 1.0.0
     */
    public $auth = false;

    /**
     * A value indicating whether the controller returns AJAX content.
     *
     * @var   boolean
     * @since 1.0.0
     */
    public $ajax = true;

    /**
     * {@inheritDoc}
     *
     * @return boolean
     * @since  1.0.0
     */
    public function display() : bool
    {
        $this->ajax = true;

        if (php_sapi_name() !== 'cli') {
            $this->ajaxRender($this->trans('Forbidden call', [], 'Modules.Pitticafeed.Front') . PHP_EOL);

            return false;
        }
        
        $result = $this
            ->get('pittica.prestashop.module.feed.tools.updater')
            ->generate(true);

        if ($result) {
            $this->ajaxRender($this->trans('Done', [], 'Modules.Pitticafeed.Front') . PHP_EOL);

            return true;
        } else {
            $this->ajaxRender($this->trans('Error', [], 'Modules.Pitticafeed.Front') . PHP_EOL);

            return false;
        }
    }
}
