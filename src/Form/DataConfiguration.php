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

use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;

/**
 * Generic data configuration.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/DataConfiguration.php
 * @since    1.0.0
 */
class DataConfiguration implements DataConfigurationInterface
{
    /**
     * Configuration.
     *
     * @var   ConfigurationInterface
     * @since 1.0.0
     */
    private $_configuration;

    /**
     * Creates an instance of the DataConfiguration class.
     *
     * @param ConfigurationInterface $configuration Configuration.
     *
     * @since 1.0.0
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->_configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     * @since  1.0.0
     */
    public function getConfiguration() : array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @param array $configuration Configuration.
     *
     * @return array Errors.
     * @since  1.0.0
     */
    public function updateConfiguration(array $configuration) : array
    {
        return [];
    }
    
    /**
     * Ensure the parameters passed are valid.
     *
     * @param array $configuration Configuration.
     *
     * @return boolean Returns true if no exception are thrown.
     * @since  1.0.0
     */
    public function validateConfiguration(array $configuration) : bool
    {
        return true;
    }

    /**
     * Gets a configuration value.
     *
     * @param string $key Configuration key.
     *
     * @return mixed
     * @since  1.0.0
     */
    protected function get(string $key)
    {
        return $this->_configuration->get($key);
    }

    /**
     * Sets a configuration value.
     *
     * @param string $key   Configuration key.
     * @param mixed  $value Value.
     *
     * @return DataConfiguration
     * @since  1.0.0
     */
    protected function set(string $key, $value) : DataConfiguration
    {
        $this->_configuration->set($key, $value);

        return $this;
    }
}
