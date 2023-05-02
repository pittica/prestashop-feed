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

namespace Pittica\PrestaShop\Module\Feed\Search\Filters;

use PrestaShop\PrestaShop\Core\Search\Filters;
use Pittica\PrestaShop\Module\Feed\Grid\Definition\Factory\OfferDefinitionFactory;

/**
 * Offer filters.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Search/Filters/OfferFilters.php
 * @since    1.0.0
 */
class OfferFilters extends Filters
{
    /**
     * {@inheritDoc}
     *
     * @var   string
     * @since 1.0.0
     */
    protected $filterId = OfferDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     *
     * @return array
     * @since  1.0.0
     */
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_product',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}
