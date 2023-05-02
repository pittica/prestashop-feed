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

use Category;
use Pittica\PrestaShop\Module\Feed\Entity\PitticaFeedOffer;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Shopalike provider class.
 *
 * @category Provider
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Provider/ShopalikeProvider.php
 * @since    1.0.0
 */
class ShopalikeProvider extends Provider
{
    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    public function getFilename() : string
    {
        return 'shopalike';
    }

    /**
     * Gets the file extension.
     *
     * @return string
     * @since  1.0.0
     */
    public function getExtension() : string
    {
        return 'csv';
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
        $handler = fopen($this->getFilePath($shopId), 'w');

        $offers   = $this
            ->getService('pittica.prestashop.module.feed.repository.offer')
            ->findByShop($shopId);
        $tools    = $this
            ->getService('prestashop.adapter.tools');
        $currency = $this
            ->getService('prestashop.adapter.data_provider.currency');
        $carrier  = $this
            ->getService('pittica.prestashop.module.feed.tools.generator')
            ->getCarrier($shopId);
        $language = (int) $this->getConfiguration()->get('PS_LANG_DEFAULT', null, ShopConstraint::shop($shopId));

        $color    = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_COLOR');
        $size     = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_SIZE');
        $gender   = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_GENDER');
        $material = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_MATERIAL');
        $age      = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_AGE');
        $energy   = $this->_getAttributeKey('PITTICA_FEED_SHOPALIKE_ENERGYCONSUMPTION');
        $heel     = $this->_getAttributeKey('PITTICA_FEED_HEEL_HEIGHT');

        fputcsv(
            $handler,
            [
                'SKU',
                'Nome prodotto',
                'Categoria',
                'Descrizione prodotto',
                'Genere',
                'Age Group',
                'Colore',
                'Marca',
                'Materiale',
                'Altezza tacco',
                'Prezzo scontato',
                'Prezzo',
                'Prezzo base',
                'Valuta',
                'Disponibilità',
                'Tempo di consegna',
                'Costi di spedizione',
                'Taglia',
                'Classe energetica',
                'Etichetta energetica',
                'Scheda informativa del prodotto',
                'URL immagine',
                'Deep URL',
                'EAN',
                'Aux URL immagine 1',
                'Aux URL immagine 2',
                'Aux URL immagine 3',
                'Aux URL immagine 4',
            ]
        );

        foreach ($offers as $offer) {
            fputcsv(
                $handler,
                [
                    $offer->getPartNumberOrEanCode(),
                    $offer->getName(),
                    $this->_getCategory($offer, $language),
                    $offer->getDescription(),
                    $this->_getAttribute($offer, $language, $gender),
                    $this->_getAttribute($offer, $language, $age),
                    $this->_getAttribute($offer, $language, $color),
                    $offer->getBrand(),
                    $this->_getAttribute($offer, $language, $material),
                    $this->_getAttribute($offer, $language, $heel),
                    (string) $tools->round($offer->getPrice(), 2),
                    (string) $tools->round($offer->getOriginalPrice(), 2),
                    '',
                    $currency->getCurrencyById($offer->getCurrencyId())->name,
                    (string) $offer->getStock(),
                    $carrier->delay[$language],
                    (string) $tools->round($offer->getShippingCost(), 2),
                    $this->_getAttribute($offer, $language, $size),
                    $this->_getAttribute($offer, $language, $energy),
                    '',
                    '',
                    $offer->getImageFirst(),
                    $offer->getLink(),
                    $offer->getEanCode(),
                    $offer->getImageSecond(),
                    $offer->getImageThird(),
                    '',
                    '',
                ]
            );
        }

        fclose($handler);

        return true;
    }

    /**
     * Gets the attribute ID.
     *
     * @param string $key Configuration key.
     *
     * @return integer|null
     * @since  1.0.0
     */
    private function _getAttributeKey(string $key) : ?int
    {
        $value = (int) $this->getConfiguration()->get($key);

        if ($value > 0) {
            return $value;
        }

        return null;
    }

    /**
     * Gets the attribute value.
     *
     * @param PitticaFeedOffer $offer       Offer.
     * @param integer          $languageId  Language ID.
     * @param integer|null     $attributeId Attribute ID.
     *
     * @return string
     * @since  1.0.0
     */
    private function _getAttribute(PitticaFeedOffer $offer, int $languageId, ?int $attributeId = null) : string
    {
        if ($attributeId !== null) {
            $dbPrefix   = $this->getContainer()->getParameter('database_prefix');
            $results    = $this
                ->getService('doctrine.orm.entity_manager')
                ->getConnection()
                ->createQueryBuilder()
                ->from(
                    $dbPrefix . 'product_attribute_combination',
                    'pac'
                )
                ->select(['al.name'])
                ->setParameter('lang', (int) $languageId)
                ->setParameter('attribute', (int) $attributeId)
                ->setParameter('pac', (int) $offer->getProductAttributeId())
                ->innerJoin(
                    'pac',
                    $dbPrefix . 'attribute',
                    'a',
                    'a.id_attribute = pac.id_attribute'
                )
                ->innerJoin(
                    'a',
                    $dbPrefix . 'attribute_lang',
                    'al',
                    'al.id_attribute = a.id_attribute AND al.id_lang = :lang'
                )
                ->where('pac.id_product_attribute = :pac')
                ->andWhere('pac.id_attribute = :attribute')
                ->execute()
                ->fetchAll();
        
            if (!empty($results[0])) {
                return $results[0]['name'];
            }
        }

        return '';
    }

    /**
     * Gets the category.
     *
     * @param PitticaFeedOffer $offer      Offer.
     * @param integer          $languageId Language ID.
     *
     * @return string
     * @since  1.0.0
     */
    private function _getCategory(PitticaFeedOffer $offer, int $languageId) : string
    {
        $id = $offer->getCategoryId();

        if ($id !== null) {
            $constraint = ShopConstraint::shop($offer->getShopId());
            $root       = $this->getConfiguration()->get('PS_ROOT_CATEGORY', null, $constraint);
            $home       = $this->getConfiguration()->get('PS_HOME_CATEGORY', null, $constraint);
            $list       = [];
            $categories = [];

            foreach (Category::getSimpleCategoriesWithParentInfos($languageId) as $category) {
                if ($category['id_category'] != $root && $category['id_category'] != $home) {
                    $list[$category['id_category']] = $category;
                }
            }

            while (isset($list[$id])) {
                $categories[] = $list[$id]['name'];
                $id = $list[$id]['id_parent'];
            }

            return implode(' > ', array_reverse($categories, true));
        } else {
            return '';
        }
    }
}
