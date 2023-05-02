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

namespace Pittica\PrestaShop\Module\Feed\Repository;

use Product;
use Doctrine\ORM\EntityRepository;
use Pittica\PrestaShop\Module\Feed\Entity\PitticaFeedOffer;

/**
 * Represents the offer repository.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Repository/PitticaFeedOfferRepository.php
 * @since    1.0.0
 */
final class PitticaFeedOfferRepository extends EntityRepository
{
    /**
     * Finds one entity or creates it by the given ID.
     *
     * @param integer      $shopId             Shop ID.
     * @param integer      $productId          Product ID.
     * @param integer|null $productAttributeId Attribute ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function findOneOrCreate(int $shopId, int $productId, ?int $productAttributeId = null) : PitticaFeedOffer
    {
        $entity = $this->findOneBy([
            'shopId'             => $shopId,
            'productId'          => $productId,
            'productAttributeId' => $productAttributeId ? $productAttributeId : 0,
        ]);

        if ($entity === null) {
            $entity = new PitticaFeedOffer();
            $entity->setShopId($shopId);
            $entity->setProductId($productId);
            $entity->setProductAttributeId($productAttributeId ? $productAttributeId : 0);
        }

        return $entity;
    }

    /**
     * Finds all offers by shop.
     *
     * @param integer|null $shopId Shop ID.
     *
     * @return array
     * @since  1.0.0
     */
    public function findByShop(?int $shopId = null) : array
    {
        $conditions = ['active' => true];

        if ($shopId) {
            $conditions['shopId'] = $shopId;
        }

        return $this->findBy($conditions);
    }
    
    /**
     * Finds one entity or creates it by the given ID.
     *
     * @param Product      $product            Product object.
     * @param integer|null $productAttributeId Attribute ID.
     * @param integer      $shopId             Shop ID.
     * @param integer      $languageId         Language ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function findOneOrCreateByProduct(Product $product, ?int $productAttributeId, int $shopId, int $languageId) : PitticaFeedOffer
    {
        $offer = $this->findOneOrCreate($shopId, $product->id, $productAttributeId);
       
        $offer
            ->setBrand($product->manufacturer_name)
            ->setActive($product->active ? $product->active : false)
            ->setDescription(trim(trim(strip_tags(is_array($product->description_short) ? $product->description_short[$languageId] : $product->description_short), PHP_EOL), ' '));

        return $offer;
    }

    /**
     * Erases the table.
     *
     * @param integer|null $shopId An option Shop ID.
     *
     * @return integer The number of deleted lines.
     * @since  1.0.0
     */
    public function erase(?int $shopId = null) : int
    {
        if (!$shopId) {
            $connection = $this->getEntityManager()->getConnection();
            return $connection->executeUpdate($connection->getDatabasePlatform()->getTruncateTableSQL(_DB_PREFIX_ . 'pittica_feed_offer', true));
        } else {
            return $this
                ->createQueryBuilder('a')
                ->delete()
                ->where('a.shopId = :shop')
                ->setParameter('shop', $shopId)
                ->getQuery()
                ->execute();
        }
    }
}
