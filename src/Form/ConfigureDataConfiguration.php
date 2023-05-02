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
 * Configure data configuration.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/ConfigureDataConfiguration.php
 * @since    1.0.0
 */
class ConfigureDataConfiguration extends DataConfiguration
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
            'skip_empty'  => $this->get('PITTICA_FEED_SKIP_EMPTY'),
            'only_active' => $this->get('PITTICA_FEED_ONLY_ACTIVE'),
            'carrier'     => $this->get('PITTICA_FEED_CARRIER'),
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
        $this
            ->set('PITTICA_FEED_SKIP_EMPTY', $configuration['skip_empty'])
            ->set('PITTICA_FEED_ONLY_ACTIVE', $configuration['only_active'])
            ->set('PITTICA_FEED_CARRIER', $configuration['carrier']);

        return [];
    }
}
