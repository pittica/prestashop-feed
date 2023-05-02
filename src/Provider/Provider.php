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

use Shop;
use ShopUrl;
use Pittica\PrestaShop\Module\Feed\ServiceTrait;
use PrestaShop\PrestaShop\Adapter\Configuration;
use Pittica\PrestaShop\Module\Feed\ConfigurationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Base provider class.
 *
 * @category Provider
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Provider/Provider.php
 * @since    1.0.0
 */
abstract class Provider
{
    use ServiceTrait;
    use ConfigurationTrait;

    /**
     * Creates an instance of the Provider class.
     *
     * @param ContainerInterface $container     Container.
     * @param Configuration      $configuration Configuration.
     *
     * @since 1.0.0
     */
    public function __construct(ContainerInterface $container, Configuration $configuration)
    {
        $this->_container     = $container;
        $this->_configuration = $configuration;
    }

    /**
     * Gets the filename.
     *
     * @return string
     * @since  1.0.0
     */
    abstract public function getFilename() : string;

    /**
     * Gets the file extension.
     *
     * @return string
     * @since  1.0.0
     */
    public function getExtension() : string
    {
        return 'xml';
    }

    /**
     * Gets the file path.
     *
     * @param integer|null $shopId Shop ID.
     *
     * @return string
     * @since  1.0.0
     */
    public function getFilePath(?int $shopId = null) : string
    {
        return $this
            ->getService('pittica.prestashop.module.feed.tools.locator')
            ->getFilePath($this->getFilename(), $shopId, $this->getExtension());
    }
    
    /**
     * Writes the document.
     *
     * @param integer|null $shopId Shop ID.
     *
     * @return boolean
     * @since  1.0.0
     */
    public function generate(?int $shopId = null) : bool
    {
        $shops = $shopId === null ? $this->getService('prestashop.adapter.shop.context')->getAllShopIds() : [$shopId];

        foreach ($shops as $shop) {
            if (!$this->generateShop((int) $shop)) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * Writes the document for the given shop.
     *
     * @param integer $shopId Shop ID.
     *
     * @return boolean
     * @since  1.0.0
     */
    abstract protected function generateShop(int $shopId) : bool;

    /**
     * Gets the shop's description.
     *
     * @param integer $shopId Shop ID.
     *
     * @return string
     * @since  1.0.0
     */
    protected function getShopDescription(int $shopId) : string
    {
        $dbPrefix = $this->getContainer()->getParameter('database_prefix');

        $index = $this
            ->getService('doctrine.orm.entity_manager')
            ->getConnection()
            ->createQueryBuilder()
            ->from($dbPrefix . 'meta', 'm')
            ->select('ml.description')
            ->setParameter('shop', $shopId)
            ->setParameter('lang', (int) $this->getConfiguration()->get('PS_LANG_DEFAULT', null, ShopConstraint::shop($shopId)))
            ->innerJoin(
                'm',
                $dbPrefix . 'meta_lang',
                'ml',
                'ml.id_meta = m.id_meta AND ml.id_shop = :shop AND ml.id_lang = :lang'
            )
            ->where("m.page = 'index'")
            ->execute()
            ->fetchAll();

        if (!empty($index)) {
            return $index[0]["description"];
        }

        return '';
    }

    /**
     * Gets the shop's name.
     *
     * @param integer $shopId Shop ID.
     *
     * @return string
     * @since  1.0.0
     */
    protected function getShopName(int $shopId) : string
    {
        $shop = new Shop($shopId);

        return (string) $shop->name;
    }

    /**
     * Gets the shop's name.
     *
     * @param integer $shopId Shop ID.
     *
     * @return string
     * @since  1.0.0
     */
    protected function getShopUrl(int $shopId) : string
    {
        if ($this->getConfiguration()->get('PS_SSL_ENABLED', null, ShopConstraint::shop($shopId))) {
            return 'https://' . ShopUrl::getMainShopDomainSSL($shopId);
        } else {
            return 'http://' . ShopUrl::getMainShopDomain($shopId);
        }
    }
}
