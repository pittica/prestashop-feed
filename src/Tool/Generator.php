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

use Tools;
use Carrier;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Generator.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Generator.php
 * @since    1.0.0
 */
class Generator extends Tool
{
    /**
     * Generates the secuirty token.
     *
     * @return string
     * @since  1.0.0
     */
    public function getToken() : string
    {
        return Tools::hash(
            $this
                ->getConfiguration()
                ->get('PS_SHOP_DOMAIN')
        );
    }

    /**
     * Generates the providers list.
     *
     * @return array
     * @since  1.0.0
     */
    public function getProviders() : array
    {
        return [
            'google',
            'trovaprezzi',
            'shopalike'
        ];
    }

    /**
     * Gets the carrier as setted in configuration or the default one.
     *
     * @param integer|null $shopId Shop ID.
     *
     * @return Carrier|null
     * @since  1.0.0
     */
    public function getCarrier(?int $shopId = null) : ?Carrier
    {
        $configuration = $this->getConfiguration();
        $carrier       = null;

        if ($shopId) {
            $constraint = ShopConstraint::shop($shopId);
            $carrier    = Carrier::getCarrierByReference((int) $configuration->get('PITTICA_FEED_CARRIER', null, null, $constraint));
        } else {
            $carrier = Carrier::getCarrierByReference((int) $configuration->get('PITTICA_FEED_CARRIER'));
        }
        
        if (!$carrier) {
            $carriers = Carrier::getCarriers((int) $configuration->get('PS_LANG_DEFAULT'), true);
            reset($carriers);

            $carrier = new Carrier(!empty($carriers[0]['id_carrier']) ? (int) $carriers[0]['id_carrier'] : null);
        }

        return $carrier ? $carrier : null;
    }
}
