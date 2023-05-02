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

namespace Pittica\PrestaShop\Module\Feed\Provider;

use Currency;
use XmlWriter;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Google Merchant provider class.
 *
 * @category Provider
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Provider/GoogleProvider.php
 * @since    1.0.0
 */
class GoogleProvider extends Provider
{
    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    public function getFilename() : string
    {
        return 'google';
    }
    
    /**
     * Writes the document for the given shop.
     *
     * @param integer $shopId Shop ID.
     *
     * @return boolean
     * @since  1.0.0
     */
    protected function generateShop(int $shopId) : bool
    {
        $xml      = new XmlWriter();
        $currency = $this->getService('prestashop.adapter.data_provider.currency');
        $carrier  = $this
            ->getService('pittica.prestashop.module.feed.tools.generator')
            ->getCarrier($shopId);
        $country  = $this
            ->getService('prestashop.adapter.data_provider.country')
            ->getIsoCodebyId();
        $locale   = $this->getService('prestashop.core.localization.locale.context_locale');

        $xml->openUri($this->getFilePath($shopId));
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('rss');
        $xml->writeAttribute('version', '2.0');
        $xml->WriteAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $xml->startElement('channel');
        
        $xml->writeElement('title', $this->getShopName($shopId));
        $xml->writeElement('link', $this->getShopUrl($shopId));
        $xml->writeElement('description', $this->getShopDescription($shopId));

        $offers = $this
            ->getService('pittica.prestashop.module.feed.repository.offer')
            ->findByShop($shopId);

        foreach ($offers as $offer) {
            $iso = $currency->getCurrencyById($offer->getCurrencyId())->iso_code;

            if (!$iso) {
                $iso = (new Currency($this->getConfiguration()->get('PS_CURRENCY_DEFAULT', null, ShopConstraint::shop($shopId))))->iso_code;
            }

            $xml->startElement('item');

            $xml->writeElement('title', $offer->getName());
            $xml->writeElement('description', !empty($offer->getDescription()) ? $offer->getDescription() : $offer->getName());
            $xml->writeElement('link', $offer->getLink());
            $xml->writeElement('g:id', $offer->getUniqueCode());
            $xml->writeElement('g:image_link', $offer->getImageFirst());
            $xml->writeElement('g:price', $locale->formatPrice($offer->getOriginalPrice(), $iso));

            if ($offer->hasSalePrice()) {
                $xml->writeElement('g:sale_price', $locale->formatPrice($offer->getPrice(), $iso));
            }

            $xml->writeElement('g:gtin', !empty($offer->getEanCode()) ? $offer->getEanCode() : $offer->getPartNumber());
            $xml->writeElement('g:brand', $offer->getBrand());
            $xml->writeElement('g:availability', $offer->getStock() > 0 ? 'in stock' : 'out of stock');
            $xml->startElement('g:shipping');
            $xml->writeElement('g:country', $country);
            $xml->writeElement('g:service', $carrier->name);
            $xml->writeElement('g:price', $locale->formatPrice($offer->getShippingCost(), $iso));
            $xml->endElement();

            if (!empty($offer->getImageSecond())) {
                $xml->writeElement('g:additional_image_link', $offer->getImageSecond());
            }

            if (!empty($offer->getImageThird())) {
                $xml->writeElement('g:additional_image_link', $offer->getImageThird());
            }

            $xml->endElement();
        }

        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
        $xml->flush();

        return true;
    }
}
