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

namespace Pittica\PrestaShop\Module\Feed;

use PrestaShop\PrestaShop\Adapter\Configuration;

/**
 * Service trait.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/ConfigurationTrait.php
 * @since    1.0.0
 */
trait ConfigurationTrait
{
    /**
     * Configuration.
     *
     * @var   Configuration|null
     * @since 1.0.0
     */
    private $_configuration = null;

    /**
     * Gets the configuration manager.
     *
     * @return Configuration
     * @since  1.0.0
     */
    protected function getConfiguration() : Configuration
    {
        return $this->_configuration;
    }
}
