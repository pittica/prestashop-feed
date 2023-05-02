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

/**
 * Shopalike data configuration.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/ShopalikeDataConfiguration.php
 * @since    1.0.0
 */
class ShopalikeDataConfiguration extends DataConfiguration
{
    /**
     * {@inheritdoc}
     *
     * @return array
     * @since  1.0.0
     */
    public function getConfiguration() : array
    {
        return [
            'active'             => $this->get('PITTICA_FEED_SHOPALIKE_ACTIVE'),
            'color'              => $this->get('PITTICA_FEED_SHOPALIKE_COLOR'),
            'size'               => $this->get('PITTICA_FEED_SHOPALIKE_SIZE'),
            'gender'             => $this->get('PITTICA_FEED_SHOPALIKE_GENDER'),
            'material'           => $this->get('PITTICA_FEED_SHOPALIKE_MATERIAL'),
            'age'                => $this->get('PITTICA_FEED_SHOPALIKE_AGE'),
            'energy_consumption' => $this->get('PITTICA_FEED_SHOPALIKE_ENERGYCONSUMPTION'),
            'heel_height'        => $this->get('PITTICA_FEED_HEEL_HEIGHT'),
        ];
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
        $this->set('PITTICA_FEED_SHOPALIKE_ACTIVE', $configuration['active']);
        $this->set('PITTICA_FEED_SHOPALIKE_COLOR', $configuration['color']);
        $this->set('PITTICA_FEED_SHOPALIKE_SIZE', $configuration['size']);
        $this->set('PITTICA_FEED_SHOPALIKE_GENDER', $configuration['gender']);
        $this->set('PITTICA_FEED_SHOPALIKE_MATERIAL', $configuration['material']);
        $this->set('PITTICA_FEED_SHOPALIKE_AGE', $configuration['age']);
        $this->set('PITTICA_FEED_SHOPALIKE_ENERGYCONSUMPTION', $configuration['energy_consumption']);
        $this->set('PITTICA_FEED_HEEL_HEIGHT', $configuration['heel_height']);

        return [];
    }
}
