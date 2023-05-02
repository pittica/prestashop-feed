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

use XmlWriter;

/**
 * Trovaprezzi provider class.
 *
 * @category Provider
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Provider/TrovaprezziProvider.php
 * @since    1.0.0
 */
class TrovaprezziProvider extends Provider
{
    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    public function getFilename() : string
    {
        return 'trovaprezzi';
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
        
        $xml->openUri($this->getFilePath($shopId));
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('Products');

        $offers = $this
            ->getService('pittica.prestashop.module.feed.repository.offer')
            ->findByShop($shopId);
        $tools = $this
            ->getService('prestashop.adapter.tools');

        foreach ($offers as $offer) {
            $xml->startElement('Offer');

            $xml->writeElement('Name', $offer->getName());
            $xml->writeElement('Brand', $offer->getBrand());
            $xml->writeElement('Description', $offer->getDescription());
            $xml->writeElement('OriginalPrice', (string) $tools->round($offer->getOriginalPrice(), 2));
            $xml->writeElement('Price', (string) $tools->round($offer->getPrice(), 2));
            $xml->writeElement('Code', $offer->getUniqueCode());
            $xml->writeElement('Link', $offer->getLink());
            $xml->writeElement('Stock', (string) $offer->getStock());
            $xml->writeElement('Categories', $offer->getCategories());
            $xml->writeElement('Image', $offer->getImageFirst());
            $xml->writeElement('ShippingCost', (string) $tools->round($offer->getShippingCost(), 2));
            $xml->writeElement('PartNumber', $offer->getPartNumberOrEanCode());
            $xml->writeElement('EanCode', $offer->getEanCode());
            $xml->writeElement('Weight', (string) $tools->round($offer->getWeight(), 2));

            if ($offer->getImageSecond()) {
                $xml->writeElement('Image2', $offer->getImageSecond());
            }

            if ($offer->getImageThird()) {
                $xml->writeElement('Image3', $offer->getImageThird());
            }

            $xml->endElement();
        }


        $xml->endElement();
        $xml->endDocument();
        $xml->flush();

        return true;
    }
}
