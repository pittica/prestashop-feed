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

use PrestaShop\PrestaShop\Adapter\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pittica\PrestaShop\Module\Feed\ServiceTrait;
use Pittica\PrestaShop\Module\Feed\ConfigurationTrait;

/**
 * Tool base class.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Tool.php
 * @since    1.0.0
 */
abstract class Tool
{
    use ServiceTrait;
    use ConfigurationTrait;

    /**
     * Creates an instance of the Tool class.
     *
     * @param ContainerInterface $container     Container.
     * @param Configuration      $configuration Configuration.
     *
     * @since 1.0.0
     */
    public function __construct(ContainerInterface $container, Configuration $configuration)
    {
        $this->_container     = $container;
        $this->_configuration = $configuration;
    }
}
