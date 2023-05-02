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

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Service trait.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/ServiceTrait.php
 * @since    1.0.0
 */
trait ServiceTrait
{
    /**
     * Container.
     *
     * @var   ContainerInterface
     * @since 1.0.0
     */
    private $_container;

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     * @since  1.0.0
     */
    protected function getContainer() : ContainerInterface
    {
        return $this->_container;
    }
    
    /**
     * Gets a service container.
     *
     * @param string $id Service ID.
     *
     * @return object|null
     * @since  1.0.0
     */
    protected function getService(string $id) : ?object
    {
        return $this->getContainer()->get($id);
    }
}
