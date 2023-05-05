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

use Cart;
use Shop;
use Country;
use Product;
use Currency;
use Employee;
use FrontController;
use Doctrine\ORM\EntityManager;
use PrestaShop\PrestaShop\Adapter\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Updater.
 *
 * @category Tools
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Tool/Updater.php
 * @since    1.0.0
 */
class Updater extends Tool
{
    /**
     * Entity Manager.
     *
     * @var   EntityManager|null
     * @since 1.0.0
     */
    private $_em = null;
    
    /**
     * Databse tables prefix.
     *
     * @var   string
     * @since 1.0.0
     */
    private $_dbPrefix;

    /**
     * Creates an instance of the Updater class.
     *
     * @param ContainerInterface $container     Container.
     * @param Configuration      $configuration Configuration.
     * @param EntityManager      $em            Entity Manager.
     * @param string             $dbPrefix      Database prefix.
     *
     * @since 1.0.0
     */
    public function __construct(ContainerInterface $container, Configuration $configuration, EntityManager $em, string $dbPrefix)
    {
        parent::__construct($container, $configuration);

        $this->_em       = $em;
        $this->_dbPrefix = $dbPrefix;
    }

    /**
     * Updates the products data.
     *
     * @param integer $shopId Shop ID.
     *
     * @return boolean
     * @since  1.0.0
     */
    public function updateProducts(?int $shopId = null) : bool
    {
        $repository = $this->getService('pittica.prestashop.module.feed.repository.offer');
        $repository->erase($shopId);
        
        $group    = (int) $this->getConfiguration()->get('PS_GUEST_GROUP');
        $context  = $this
            ->getService('prestashop.adapter.legacy.context')
            ->getContext();
        $image    = $this
            ->getService('pittica.prestashop.module.feed.tools.image');
        $price    = $this
            ->getService('pittica.prestashop.module.feed.tools.price');
        $employee = new Employee();
        $shops    = $this->_filterShops($shopId);

        $context->controller = new FrontController();
        $context->employee   = $employee;

        if ($gc = gc_enabled()) {
            gc_disable();
        }
        
        foreach ($shops as $shop) {
            $shop = (int) $shop;

            Shop::setContext(Shop::CONTEXT_ALL, $shop);

            $constraint = ShopConstraint::shop($shop);
            $language   = (int) $this->getConfiguration()->get('PS_LANG_DEFAULT', null, $constraint);
            $free       = (float) $this->getConfiguration()->get('PS_SHIPPING_FREE_PRICE', null, $constraint);
            $carrier    = $this
                ->getService('pittica.prestashop.module.feed.tools.generator')
                ->getCarrier($shop);
            $currency   = new Currency($this->getConfiguration()->get('PS_CURRENCY_DEFAULT', null, $constraint));
            $country    = new Country((int) $this->getConfiguration()->get('PS_COUNTRY_DEFAULT', null, $constraint));

            $context->country  = $country;
            $context->shop     = new Shop($shop);
            $context->currency = $currency;

            if ($free <= 0.0) {
                $free = null;
            }
            
            foreach ($this->getProducts($shop) as $p) {
                $product    = new Product((int) $p['id'], $language, $shop);
                $attributes = $this->getProductAttributes($product->id, $shop);

                if (!empty($attributes)) {
                    foreach ($attributes as $attribute) {
                        $cart = $this->createCart($language, $currency->id, $carrier->id, $shop);

                        $context->cart = $cart;

                        $offer = $repository
                            ->findOneOrCreateByProduct($product, (int) $attribute['id_product_attribute'], $shop, $language)
                            ->setCategoryId($p['category_default'] === null ? null : (int) $p['category_default'])
                            ->setCurrencyId($currency->id)
                            ->setName($attribute['designation'])
                            ->setLink($context->link->getProductLink($product, null, $p['category_default_rewrite'], null, null, $shop, (int) $attribute['id_product_attribute']))
                            ->setStock((int) $attribute['quantity'])
                            ->setCategories($p['categories'])
                            ->setWeight((float) $attribute['weight'])
                            ->setPartNumber($attribute['reference'])
                            ->setEanCode($attribute['ean13']);
                            
                        $price->updatePrices($offer, (int) $attribute['minimal_quantity'], $country->id, $group);
                        $price
                            ->updateShippingCost(
                                $offer,
                                $cart,
                                $country,
                                $carrier->id,
                                (int) $attribute['minimal_quantity'],
                                (float) $product->additional_shipping_cost,
                                (bool) $product->is_virtual,
                                $free
                            );

                        $image
                                ->populate($offer, $product, $language, $shop);

                        $this->_em->persist($offer);
                        $this->_em->flush();

                        $cart->delete();
                    }
                } else {
                    $cart = $this->createCart($language, $currency->id, $carrier->id, $shop);

                    $context->cart = $cart;

                    $minimal = $product->minimal_quantity > 0 ? (int) $product->minimal_quantity : 1;

                    $offer = $repository
                        ->findOneOrCreateByProduct($product, null, $shop, $language)
                        ->setCategoryId($p['category_default'] === null ? null : (int) $p['category_default'])
                        ->setCurrencyId($currency->id)
                        ->setName(is_array($product->name) ? $product->name[$language] : $product->name)
                        ->setLink($context->link->getProductLink($product, null, $p['category_default_rewrite'], null, null, $shop))
                        ->setStock((int) $product->quantity)
                        ->setCategories($p['categories'])
                        ->setWeight((float) $product->weight)
                        ->setPartNumber(!empty($product->reference) ? $product->reference : $product->ean13)
                        ->setEanCode($product->ean13);

                    $price->updatePrices($offer, $minimal, $country->id, $group);
                    $price
                        ->updateShippingCost(
                            $offer,
                            $cart,
                            $country,
                            $carrier->id,
                            $minimal,
                            (float) $product->additional_shipping_cost,
                            (bool) $product->is_virtual,
                            $free
                        );

                    $image
                        ->populate($offer, $product, $language, $shop);
                        
                    $this->_em->persist($offer);
                    $this->_em->flush();

                    $cart->delete();
                }
                    
                Product::resetStaticCache();
            }
        }

        if ($gc) {
            gc_enable();
        }

        return true;
    }

    /**
     * Generates the feeds.
     *
     * @param boolean      $refresh  A value indicating whether the data requires to be refreshed.
     * @param string|null  $provider Feed provider.
     * @param integer|null $shopId   Shop ID.
     *
     * @return boolean Returns "True" whether the XML file has been generated; otherwise, "False".
     * @since  1.0.0
     */
    public function generate($refresh = true, ?string $provider = null, ?int $shopId = null) : bool
    {
        if ($refresh) {
            $this->updateProducts();
        }

        $shops     = $this->_filterShops($shopId);
        $providers = $this
            ->getService('pittica.prestashop.module.feed.tools.generator')
            ->getProviders();

        if ($provider) {
            $name = strtolower($provider);

            if ($provider && in_array(strtolower($name), $providers)) {
                $providers = [$name];
            }
        }

        foreach ($providers as $p) {
            $object = $this->getService('pittica.prestashop.module.feed.providers.' . $p);

            foreach ($shops as $shop) {
                $object->generate((int) $shop);
            }
        }

        return true;
    }

    /**
     * Gets the products.
     *
     * @param integer $shopId Shop ID.
     *
     * @return array
     * @since  1.0.0
     */
    protected function getProducts(int $shopId) : array
    {
        $constraint = ShopConstraint::shop($shopId);
        $qb         = $this
            ->_em
            ->getConnection()
            ->createQueryBuilder()
            ->from($this->_dbPrefix . 'product', 'p')
            ->select(
                [
                    'p.id_product AS id',
                    'cd.link_rewrite AS category_default_rewrite',
                    'cd.id_category AS category_default',
                    "GROUP_CONCAT(DISTINCT cl.name SEPARATOR ', ') AS categories",
                ]
            )
            ->setParameter('shop', $shopId)
            ->setParameter('lang', (int) $this->getConfiguration()->get('PS_LANG_DEFAULT', null, $constraint))
            ->setParameter('root', (int) $this->getConfiguration()->get('PS_ROOT_CATEGORY', null, $constraint))
            ->setParameter('home', (int) $this->getConfiguration()->get('PS_HOME_CATEGORY', null, $constraint))
            ->setParameter('excluded', $this->getConfiguration()->get('PITTICA_FEED_EXCLUDED_CATEGORIES', null, $constraint))
            ->innerJoin(
                'p',
                $this->_dbPrefix . 'product_shop',
                'ps',
                'ps.id_product = p.id_product AND ps.id_shop = :shop'
            )
            ->innerJoin(
                'ps',
                $this->_dbPrefix . 'shop',
                's',
                's.id_shop = ps.id_shop AND ps.id_shop = :shop AND s.active = 1'
            )
            ->leftJoin(
                'p',
                $this->_dbPrefix . 'category_lang',
                'cd',
                '(cd.id_category = p.id_category_default OR cd.id_category = ps.id_category_default) AND cd.id_lang = :lang AND cd.id_category != :root AND cd.id_category != :home AND cd.id_category NOT IN(:excluded)'
            )
            ->leftJoin(
                'cp',
                $this->_dbPrefix . 'category',
                'c',
                'c.id_category = cp.id_category AND c.id_category != :home AND c.id_category != :root'
            )
            ->leftJoin(
                'ps',
                $this->_dbPrefix . 'category_product',
                'cp',
                'cp.id_product = ps.id_product'
            )
            ->leftJoin(
                'cp',
                $this->_dbPrefix . 'category_lang',
                'cl',
                'cl.id_category = c.id_category AND cl.id_lang = :lang'
            )
            ->groupBy('p.id_product');

        if ((bool) $this->getConfiguration()->get('PITTICA_FEED_ONLY_ACTIVE', true, $constraint)) {
            $qb->andWhere('ps.active = 1 OR p.active = 1');
        }

        if ((bool) $this->getConfiguration()->get('PITTICA_FEED_SKIP_EMPTY', true, $constraint)) {
            $qb
                ->innerJoin(
                    'p',
                    $this->_dbPrefix . 'stock_available',
                    'sa',
                    'sa.id_product = p.id_product AND ps.id_shop = :shop AND sa.quantity >= ps.minimal_quantity'
                );
        }

        return $qb->execute()->fetchAll();
    }

    /**
     * Gets the product attributes.
     *
     * @param integer $productId Product ID.
     * @param integer $shopId    Shop ID.
     *
     * @return array
     * @since  1.0.0
     */
    protected function getProductAttributes(int $productId, int $shopId) : array
    {
        $productId  = $productId;
        $constraint = ShopConstraint::shop($shopId);
        $qb         = $this
            ->_em
            ->getConnection()
            ->createQueryBuilder()
            ->from($this->_dbPrefix . 'product_attribute_combination', 'pac')
            ->select(
                [
                    'pac.id_product_attribute',
                    'GREATEST(pas.minimal_quantity, 1) AS minimal_quantity',
                    "CONCAT(pl.name, ' - ', GROUP_CONCAT(DISTINCT agl.name, ': ', al.name ORDER BY agl.id_attribute_group SEPARATOR ', ')) AS designation",
                    '(p.weight + pas.weight) AS weight',
                    "COALESCE(NULLIF(pa.ean13, ''), NULLIF(p.ean13, '')) AS ean13",
                    "COALESCE(NULLIF(pa.reference, ''), NULLIF(p.reference, ''), COALESCE(NULLIF(pa.ean13, ''), NULLIF(p.ean13, ''))) AS reference",
                    'SUM(DISTINCT sa.quantity) AS quantity',
                ]
            )
            ->setParameter('product', $productId)
            ->setParameter('shop', $shopId)
            ->setParameter('lang', (int) $this->getConfiguration()->get('PS_LANG_DEFAULT', null, $constraint))
            ->leftJoin(
                'pac',
                $this->_dbPrefix . 'attribute',
                'a',
                'a.id_attribute = pac.id_attribute'
            )
            ->leftJoin(
                'a',
                $this->_dbPrefix . 'attribute_group',
                'ag',
                'ag.id_attribute_group = a.id_attribute_group'
            )
            ->leftJoin(
                'a',
                $this->_dbPrefix . 'attribute_lang',
                'al',
                'al.id_attribute = a.id_attribute AND al.id_lang = :lang'
            )
            ->leftJoin(
                'ag',
                $this->_dbPrefix . 'attribute_group_lang',
                'agl',
                'agl.id_attribute_group = ag.id_attribute_group AND al.id_lang = :lang'
            )
            ->innerJoin(
                'pac',
                $this->_dbPrefix . 'product_attribute_shop',
                'pas',
                'pas.id_product_attribute = pac.id_product_attribute'
            )
            ->innerJoin(
                'pac',
                $this->_dbPrefix . 'product_attribute',
                'pa',
                'pa.id_product_attribute = pac.id_product_attribute'
            )
            ->innerJoin(
                'pas',
                $this->_dbPrefix . 'shop',
                's',
                's.id_shop = pas.id_shop AND s.id_shop = :shop AND s.active = 1'
            )
            ->innerJoin(
                'pas',
                $this->_dbPrefix . 'product',
                'p',
                'p.id_product = pas.id_product AND p.id_product = :product AND p.active = 1'
            )
            ->innerJoin(
                'p',
                $this->_dbPrefix . 'product_shop',
                'ps',
                'ps.id_product = ps.id_product'
            )
            ->innerJoin(
                'ps',
                $this->_dbPrefix . 'product_lang',
                'pl',
                'pl.id_product = ps.id_product'
            )
            ->groupBy('pac.id_product_attribute')
            ->orderBy('pac.id_product_attribute');

        if ((bool) $this->getConfiguration()->get('PITTICA_FEED_SKIP_EMPTY', true, $constraint)) {
            $qb
                ->innerJoin(
                    'ps',
                    $this->_dbPrefix . 'stock_available',
                    'sa',
                    'sa.id_product = ps.id_product AND sa.id_product_attribute = pas.id_product_attribute AND ps.id_shop = :shop AND sa.quantity >= pa.minimal_quantity'
                );
        } else {
            $qb
                ->leftJoin(
                    'ps',
                    $this->_dbPrefix . 'stock_available',
                    'sa',
                    'sa.id_product = ps.id_product AND sa.id_product_attribute = pas.id_product_attribute AND ps.id_shop = :shop'
                );
        }
        
        return $qb->execute()->fetchAll();
    }

    /**
     * Creates a Cart object.
     *
     * @param integer $languageId Language ID.
     * @param integer $currencyId Currency ID.
     * @param integer $carrierId  Carrier ID.
     * @param integer $shopId     Shop ID.
     *
     * @return Cart
     * @since  1.0.0
     */
    protected function createCart(int $languageId, int $currencyId, int $carrierId, int $shopId) : Cart
    {
        $cart              = new Cart(0);
        $cart->employee    = new Employee();
        $cart->id_currency = $currencyId;
        $cart->id_lang     = $languageId;
        $cart->id_carrier  = $carrierId;
        $cart->id_shop     = $shopId;

        return $cart;
    }

    /**
     * Filters the Shop ID.
     *
     * @param integer|null $shopId Shop ID.
     *
     * @return array
     * @since  1.0.0
     */
    private function _filterShops(?int $shopId = null) : array
    {
        return $shopId === null ? Shop::getShops(true, null, true) : [$shopId];
    }
}
