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

namespace Pittica\PrestaShop\Module\Feed\Install;

use PitticaFeed;
use Doctrine\ORM\EntityManager;
use PrestaShop\PrestaShop\Adapter\Configuration;

/**
 * Class responsible for modifications needed during installation/uninstallation of the module.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Install/Installer.php
 * @since    1.0.0
 */
class Installer
{
    /**
     * Module.
     *
     * @var   PitticaFeed
     * @since 1.0.0
     */
    private $_module;
    
    /**
     * Configuration.
     *
     * @var   Configuration
     * @since 1.0.0
     */
    private $_configuration;
    
    /**
     * Database prefix.
     *
     * @var   string
     * @since 1.0.0
     */
    private $_dbPrefix;
    
    /**
     * Entity manager.
     *
     * @var   EntityManager
     * @since 1.0.0
     */
    private $_em;

    /**
     * Creates an instance of the Installer class.
     *
     * @param PitticaFeed   $module        Main module class.
     * @param Configuration $configuration Configuration.
     * @param string        $dbPrefix      Database prefix.
     * @param EntityManager $em            Entity manager.
     *
     * @since 1.0.0
     */
    public function __construct(
        PitticaFeed $module,
        Configuration $configuration,
        string $dbPrefix,
        EntityManager $em
    ) {
        $this->_module        = $module;
        $this->_configuration = $configuration;
        $this->_dbPrefix      = $dbPrefix;
        $this->_em            = $em;
    }

    /**
     * Gets the default configuration.
     *
     * @return array
     * @since  1.0.0
     */
    public function getConfigurationDefaults() : array
    {
        $carriers = $this
            ->_module
            ->get('prestashop.adapter.data_provider.carrier')
            ->getCarriers((int) $this->_configuration->get('PS_LANG_DEFAULT'), true);
        
        reset($carriers);

        return [
            'PITTICA_FEED_CARRIER'                     => !empty($carriers[0]['id_reference']) ? (int) $carriers[0]['id_reference'] : -1,
            'PITTICA_FEED_SKIP_EMPTY'                  => true,
            'PITTICA_FEED_ONLY_ACTIVE'                 => true,
            'PITTICA_FEED_GOOGLE_ACTIVE'               => false,
            'PITTICA_FEED_TROVAPREZZI_ACTIVE'          => false,
            'PITTICA_FEED_TROVAPREZZI_TRUSTEDPROGRAM'  => null,
            'PITTICA_FEED_TROVAPREZZI_URL'             => null,
            'PITTICA_FEED_SHOPALIKE_ACTIVE'            => false,
            'PITTICA_FEED_SHOPALIKE_COLOR'             => null,
            'PITTICA_FEED_SHOPALIKE_SIZE'              => null,
            'PITTICA_FEED_SHOPALIKE_GENDER'            => null,
            'PITTICA_FEED_SHOPALIKE_MATERIAL'          => null,
            'PITTICA_FEED_SHOPALIKE_AGE'               => null,
            'PITTICA_FEED_SHOPALIKE_ENERGYCONSUMPTION' => null,
            'PITTICA_FEED_HEEL_HEIGHT'                 => null,
        ];
    }

    /**
     * Module's installation entry point.
     *
     * @return bool
     * @since  1.0.0
     */
    public function install() : bool
    {
        if (!$this->_registerHooks()) {
            return false;
        }

        if (!$this->_installDatabase()) {
            return false;
        }

        foreach ($this->getConfigurationDefaults() as $key => $value) {
            $this->_configuration->set($key, $value);
        }

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @return bool
     * @since  1.0.0
     */
    public function uninstall() : bool
    {
        foreach ($this->getConfigurationDefaults() as $key => $value) {
            $this->_configuration->remove($key);
        }

        return $this->_uninstallDatabase();
    }

    /**
     * Install the database modifications required for this module.
     *
     * @return bool
     * @since  1.0.0
     */
    private function _installDatabase() : bool
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `' . $this->_dbPrefix . 'pittica_feed_offer` (
                `id_shop` INT(10) UNSIGNED NOT NULL,
                `id_product` INT(10) UNSIGNED NOT NULL,
                `id_product_attribute` INT(10) UNSIGNED NOT NULL DEFAULT 0,
                `id_category` INT(10) UNSIGNED DEFAULT NULL,
                `id_currency` INT(10) UNSIGNED DEFAULT NULL,
                `name` TEXT NOT NULL,
                `brand` TEXT NULL,
                `description` TEXT NULL,
                `original_price` DECIMAL(20,6) NULL,
                `price` DECIMAL(20,6) NULL,
                `link` TEXT NULL,
                `stock` INT(10) UNSIGNED NOT NULL DEFAULT 0,
                `categories` TEXT NULL,
                `image_first` TEXT NULL,
                `image_second` TEXT NULL,
                `image_third` TEXT NULL,
                `part_number` TEXT NULL,
                `ean_code` TEXT NULL,
                `shipping_cost` DECIMAL(20,6) NULL,
                `weight` DECIMAL(20,6) NULL,
                `active` TINYINT NOT NULL DEFAULT 0,
                PRIMARY KEY (`id_product`, `id_product_attribute`, `id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
        ];

        return $this->_executeQueries($queries);
    }

    /**
     * Uninstall database modifications.
     *
     * @return bool
     * @since  1.0.0
     */
    private function _uninstallDatabase() : bool
    {
        $queries = [
            'DROP TABLE IF EXISTS `' . $this->_dbPrefix . 'pittica_feed_offer`;'
        ];

        return $this->_executeQueries($queries);
    }

    /**
     * Register hooks for the module.
     *
     * @return bool
     * @since  1.0.0
     */
    private function _registerHooks() : bool
    {
        return (bool) $this->_module->registerHook(
            [
                'displayFooterAfter',
                'displayOrderConfirmation',
                'actionAdminControllerSetMedia',
                'actionFrontControllerSetMedia',
            ]
        );
    }

    /**
     * A helper that executes multiple database queries.
     *
     * @param array $queries Database queries.
     *
     * @return bool
     * @since  1.0.0
     */
    private function _executeQueries(array $queries) : bool
    {
        $connection = $this->_em->getConnection();

        foreach ($queries as $query) {
            $s = $connection->prepare($query);

            if (!$s->execute()) {
                return false;
            }
        }

        return true;
    }
}
