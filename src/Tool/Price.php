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

use Product;
use Cart;
use Country;
use Pittica\PrestaShop\Module\Feed\Entity\PitticaFeedOffer;

/**
 * Price.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Price.php
 * @since    1.0.0
 */
class Price extends Tool
{
    /**
     * Calculates and updates the price.
     *
     * @param PitticaFeedOffer $offer     Offer.
     * @param integer          $quantity  Quantity.
     * @param integer          $countryId Country ID.
     * @param integer          $groupId   Group ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function updatePrices(PitticaFeedOffer $offer, int $quantity, int $countryId, int $groupId) : PitticaFeedOffer
    {
        return $offer
            ->setOriginalPrice(
                $this->calculate(
                    $offer,
                    $quantity,
                    $countryId,
                    $groupId,
                    false
                )
            )
            ->setPrice(
                $this->calculate(
                    $offer,
                    $quantity,
                    $countryId,
                    $groupId,
                    true
                )
            )
            ->setActive($offer->getPrice() > 0);
    }

    /**
     * Calculates the price.
     *
     * @param PitticaFeedOffer $offer     Offer.
     * @param integer          $quantity  Quantity.
     * @param integer          $countryId Country ID.
     * @param integer          $groupId   Group ID.
     * @param boolean          $reduction A value indicating whether use reductions.
     *
     * @return float
     * @since  1.0.0
     */
    public function calculate(PitticaFeedOffer $offer, int $quantity, int $countryId, int $groupId, bool $reduction = true) : float
    {
        $price = null;

        $result = Product::priceCalculation(
            $offer->getShopId(),
            $offer->getProductId(),
            $offer->getProductAttributeId(),
            $countryId,
            0,
            0,
            $offer->getCurrencyId(),
            $groupId,
            $quantity,
            true,
            7,
            false,
            $reduction,
            true,
            $price,
            true,
            0,
            true,
            0,
            $quantity
        );

        return $this
            ->getService('prestashop.adapter.tools')
            ->round($result, 2);
    }

    /**
     * Calculates and updates the price.
     *
     * @param PitticaFeedOffer $offer     Offer.
     * @param Cart             $cart      Cart.
     * @param Country          $country   Country.
     * @param integer          $carrierId Carrier ID.
     * @param integer          $quantity  Products quantity.
     * @param float            $cost      Additional or base shipping cost.
     * @param boolean          $isVirtual A value indicating whether the product is virtual.
     * @param float|null       $free      Free shipping limit.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function updateShippingCost(PitticaFeedOffer $offer, Cart $cart, Country $country, int $carrierId, int $quantity, float $cost = 0.00, bool $isVirtual = false, ?float $free = null) : PitticaFeedOffer
    {
        if ($free !== null && $offer->getPrice() >= (float) $free) {
            $offer->setShippingCost((float) $cost);
        } else {
            $offer->setShippingCost(
                $cart->getPackageShippingCost(
                    $carrierId,
                    true,
                    $country,
                    [
                        [
                            'id_product'               => $offer->getProductId(),
                            'id_product_attribute'     => $offer->getProductAttributeId(),
                            'id_shop'                  => $offer->getShopId(),
                            'cart_quantity'            => $quantity,
                            'is_virtual'               => $isVirtual,
                            'id_address_delivery'      => null,
                            'id_customization'         => null,
                            'weight'                   => $offer->getWeight(),
                            'additional_shipping_cost' => $cost
                        ]
                    ]
                )
            );
        }

        return $offer;
    }
}
