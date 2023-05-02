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
 * Google data configuration.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/GoogleDataConfiguration.php
 * @since    1.0.0
 */
class GoogleDataConfiguration extends DataConfiguration
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
            'active' => $this->get('PITTICA_FEED_GOOGLE_ACTIVE')
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
        $this->set('PITTICA_FEED_GOOGLE_ACTIVE', $configuration['active']);

        return [];
    }
}
