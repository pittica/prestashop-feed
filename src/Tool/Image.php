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
use Language;
use Image as BaseImage;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use Pittica\PrestaShop\Module\Feed\Entity\PitticaFeedOffer;

/**
 * Image.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Image.php
 * @since    1.0.0
 */
class Image extends Tool
{
    /**
     * Populates the images of the given offer.
     *
     * @param PitticaFeedOffer $offer      Offer object.
     * @param Product          $product    Product object.
     * @param integer          $languageId Language ID.
     * @param integer          $shopId     Shop ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function populate(PitticaFeedOffer $offer, Product $product, int $languageId, int $shopId) : PitticaFeedOffer
    {
        $context   = $this
            ->getService('prestashop.adapter.legacy.context')
            ->getContext();
        $retriever = new ImageRetriever($context->link);
        $empty     = $retriever->getNoPictureImage(new Language($languageId));
        $empty     = !empty($img['large']['url']) ? $img['large']['url'] : '';

        $images  = array();

        foreach (BaseImage::getImages($languageId, $offer->getProductId(), $offer->getProductAttributeId() ? $offer->getProductAttributeId() : null, $shopId) as $image) {
            $img = $retriever->getImage($product, $image['id_image']);

            if (!empty($img['large']['url'])) {
                $images[] = $img['large']['url'];
            }
        }

        $offer->setImageSecond(!empty($images[1]) ? $images[1] : $empty);
        $offer->setImageThird(!empty($images[2]) ? $images[2] : $empty);

        if (!empty($images[0])) {
            $offer->setImageFirst($images[0]);
        } else {
            $cover = BaseImage::getGlobalCover($offer->getProductId());

            if (!empty($cover)) {
                $offer->setImageFirst($context->link->getImageLink($this->getImageRewrite($product, $languageId), (int) $cover['id_image']));
            } else {
                $offer->setImageFirst($empty);
            }
        }

        return $offer;
    }

    /**
     * Gets the link rewrite for an image of the given product.
     *
     * @param Product $product    Product object.
     * @param int     $languageId Language ID.
     *
     * @return string
     * @since  1.0.0
     */
    public function getImageRewrite(Product $product, int $languageId) : string
    {
        if (!empty($product->link_rewrite)) {
            return is_array($product->link_rewrite) && !empty($product->link_rewrite[$languageId]) ? $product->link_rewrite[$languageId] : $product->link_rewrite;
        } else {
            return is_array($product->name) && !empty($product->name[$languageId]) ? $product->name[$languageId] : $product->name;
        }
    }
}
