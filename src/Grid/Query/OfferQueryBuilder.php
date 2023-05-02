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

namespace Pittica\PrestaShop\Module\Feed\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\SqlFilters;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\DoctrineFilterApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;

/**
 * Offer query builder.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Grid/Query/OfferQueryBuilder.php
 * @since    1.0.0
 */
final class OfferQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * Criteria applicator.
     *
     * @var   DoctrineSearchCriteriaApplicatorInterface
     * @since 1.0.0
     */
    private $_searchCriteriaApplicator;

    /**
     * Shop ID.
     *
     * @var   integer
     * @since 1.0.0
     */
    private $_contextShopId;

    /**
     * A value indicating whether the shop is in all-shops context.
     *
     * @var   boolean
     * @since 1.0.0
     */
    private $_isAllShopContext;

    /**
     * Filter applicator.
     *
     * @var   DoctrineFilterApplicatorInterface
     * @since 1.0.0
     */
    private $_filterApplicator;

    /**
     * Creates an instance of the OfferQueryBuilder class.
     *
     * @param Connection                                $connection               Connection.
     * @param string                                    $dbPrefix                 Database prefix.
     * @param DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator Criteria applicator.
     * @param integer                                   $contextShopId            Shop ID.
     * @param boolean                                   $isAllShopContext         A value indicating whether the shop is in all-shops context.
     * @param DoctrineFilterApplicatorInterface         $filterApplicator         Filter applicator.
     *
     * @since 1.0.0
     */
    public function __construct(
        Connection $connection,
        string $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator,
        int $contextShopId,
        bool $isAllShopContext,
        DoctrineFilterApplicatorInterface $filterApplicator
    ) {
        parent::__construct($connection, $dbPrefix);

        $this->_searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->_contextShopId            = $contextShopId;
        $this->_isAllShopContext         = $isAllShopContext;
        $this->_filterApplicator         = $filterApplicator;
    }

    /**
     * {@inheritDoc}
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     *
     * @return QueryBuilder
     * @since  1.0.0
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria) : QueryBuilder
    {
        $qb = $this->_getQueryBuilder($searchCriteria->getFilters());
        $qb
            ->select('o.`id_product`, o.`id_shop`, o.`id_product_attribute`, o.`name`, o.`active`')
            ->addSelect('CONCAT(o.`id_product`, "-", o.`id_product_attribute`, "-", o.`id_shop`) AS k')
            ->addSelect('s.`name` AS shop_name')
            ->addSelect('(CASE WHEN (o.image_first IS NOT NULL AND o.image_first != "") THEN 1 ELSE 0 END) AS has_image')
            ->addSelect('(CASE WHEN (o.categories IS NOT NULL AND o.categories != "") THEN 1 ELSE 0 END) AS has_categories')
            ->addSelect('(CASE WHEN ((o.part_number IS NOT NULL AND o.part_number != "") OR (o.ean_code IS NOT NULL AND o.ean_code != "")) THEN 1 ELSE 0 END) AS has_code');

        $this->_searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb);

        return $qb;
    }

    
    /**
     * {@inheritDoc}
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     *
     * @return QueryBuilder
     * @since  1.0.0
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria) : QueryBuilder
    {
        return $this
            ->_getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(CONCAT(o.`id_product`, "-", o.`id_product_attribute`, "-", o.`id_shop`))');
    }

    /**
     * Gets query builder.
     *
     * @param array $filterValues Filter values.
     *
     * @return QueryBuilder
     * @since  1.0.0
     */
    private function _getQueryBuilder(array $filterValues) : QueryBuilder
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'pittica_feed_offer', 'o');
            
        if (!$this->_isAllShopContext) {
            $qb
                ->innerJoin(
                    'o',
                    $this->dbPrefix . 'shop',
                    's',
                    'o.`id_shop` = s.`id_shop` AND s.`id_shop` = :id_shop'
                );
        } else {
            $qb
                ->leftJoin(
                    'o',
                    $this->dbPrefix . 'shop',
                    's',
                    's.`id_shop` = o.`id_shop`'
                );
        }

        $sqlFilters = new SqlFilters();
        $sqlFilters
            ->addFilter(
                'active',
                'o.`active`',
                SqlFilters::WHERE_STRICT
            )
            ->addFilter(
                'name',
                'o.`name`',
                SqlFilters::WHERE_LIKE
            )
            ->addFilter(
                'id_product',
                'o.`id_product`',
                SqlFilters::WHERE_STRICT
            );

        $this->_filterApplicator->apply($qb, $sqlFilters, $filterValues);

        $qb->setParameter('id_shop', $this->_contextShopId);

        foreach ($filterValues as $filterName => $filter) {
            switch ($filterName) {
                case 'has_image':
                    $qb
                        ->andWhere('(CASE WHEN (o.image_first IS NOT NULL AND o.image_first != "") THEN 1 ELSE 0 END) = :has_image')
                        ->setParameter('has_image', (bool)$filter);
    
                    continue;
                case 'has_categories':
                    $qb
                        ->andWhere('(CASE WHEN (o.categories IS NOT NULL AND o.categories != "") THEN 1 ELSE 0 END) = :has_categories')
                        ->setParameter('has_categories', (bool)$filter);
    
                    continue;
                case 'has_code':
                    $qb
                        ->andWhere('(CASE WHEN ((o.part_number IS NOT NULL AND o.part_number != "") OR (o.ean_code IS NOT NULL AND o.ean_code != "")) THEN 1 ELSE 0 END) = :has_code')
                        ->setParameter('has_code', (bool)$filter);
    
                    continue;
            }
        }

        return $qb;
    }
}
