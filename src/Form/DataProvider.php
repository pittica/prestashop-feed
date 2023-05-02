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

namespace Pittica\PrestaShop\Module\Feed\Form;

use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;

/**
 * Data provider.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/DataProvider.php
 * @since    1.0.0
 */
class DataProvider implements FormDataProviderInterface
{
    /**
     * Configuration.
     *
     * @var   DataConfigurationInterface
     * @since 1.0.0
     */
    private $_configuration;

    /**
     * Creates an instance of the DataProvider class.
     *
     * @param DataConfigurationInterface $configuration Configuration.
     *
     * @since 1.0.0
     */
    public function __construct(DataConfigurationInterface $configuration)
    {
        $this->_configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     * @since  1.0.0
     */
    public function getData() : array
    {
        return $this->_configuration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     * 
     * @param array $data Data.
     * 
     * @return array
     * @since  1.0.0
     */
    public function setData(array $data) : array
    {
        return $this->_configuration->updateConfiguration($data);
    }
}
