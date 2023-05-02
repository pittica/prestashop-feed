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

namespace Pittica\PrestaShop\Module\Feed\Command;

use Pittica\PrestaShop\Module\Feed\ServiceTrait;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base CLI command.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Command/Command.php
 * @since    1.0.0
 */
abstract class Command extends BaseCommand
{
    use ServiceTrait;
    
    /**
     * Creates an instance of the Command class.
     *
     * @param ContainerInterface $container Container.
     *
     * @since 1.0.0
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;

        parent::__construct();
    }
}
