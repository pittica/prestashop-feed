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

namespace Pittica\PrestaShop\Module\Feed\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\LinkColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\StatusColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;

/**
 * Offer query builder.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Grid/Definition/Factory/OfferDefinitionFactory.php
 * @since    1.0.0
 */
class OfferDefinitionFactory extends AbstractGridDefinitionFactory
{
    /**
     * Grid ID.
     *
     * @var   string
     * @since 1.0.0
     */
    const GRID_ID = 'offer';

    /**
     * A value indicating whether the shop is in all-shops context.
     *
     * @var   boolean
     * @since 1.0.0
     */
    private $_isAllShopContext;

    /**
     * Creates an instance of the OfferDefinitionFactory class.
     *
     * @param HookDispatcherInterface|null $hookDispatcher   Hook dispacer.
     * @param boolean                      $isAllShopContext A value indicating whether the shop is in all-shops context.
     *
     * @since 1.0.0
     */
    public function __construct(HookDispatcherInterface $hookDispatcher = null, bool $isAllShopContext = false)
    {
        parent::__construct($hookDispatcher);

        $this->_isAllShopContext = $isAllShopContext;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    protected function getId() : string
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    protected function getName() : string
    {
        return $this->trans('Offers', [], 'Modules.Pitticafeed.Admin');
    }

    /**
     * {@inheritdoc}
     *
     * @return BulkActionCollectionInterface
     * @since  1.0.0
     */
    protected function getBulkActions() : BulkActionCollectionInterface
    {
        return (new BulkActionCollection())
            ->add(
                (new SubmitBulkAction('enable_selection'))
                    ->setName($this->trans('Enable selection', [], 'Admin.Actions'))
                    ->setOptions(['submit_route' => 'pittica.prestashop.module.feed.check.enable_selection'])
            )
            ->add(
                (new SubmitBulkAction('disable_selection'))
                    ->setName($this->trans('Disable selection', [], 'Admin.Actions'))
                    ->setOptions(['submit_route' => 'pittica.prestashop.module.feed.check.disable_selection'])
            )
            ->add(
                (new SubmitBulkAction('delete_selection'))
                    ->setName($this->trans('Delete selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'pittica.prestashop.module.feed.check.delete_selection',
                        'confirm_message' => $this->trans('Delete selected items?', [], 'Admin.Actions'),
                    ])
            );
    }

    /**
     * {@inheritDoc}
     *
     * @return ColumnCollection
     * @since  1.0.0
     */
    protected function getColumns() : ColumnCollection
    {
        $columns = (new ColumnCollection())
            ->add(
                (new BulkActionColumn('bulk_action'))
                    ->setOptions(['bulk_field' => 'k'])
            )
            ->add(
                (new LinkColumn('id_product'))
                    ->setName($this->trans('ID', [], 'Admin.Global'))
                    ->setOptions(
                        [
                            'field'             => 'id_product',
                            'route'             => 'admin_product_form',
                            'route_param_name'  => 'id',
                            'route_param_field' => 'id_product',
                        ]
                    )
            )
            ->add(
                (new DataColumn('name'))
                    ->setName($this->trans('Name', [], 'Modules.Pitticafeed.Admin'))
                    ->setOptions(['field' => 'name'])
            )
            ->add(
                (new StatusColumn('has_code'))
                    ->setName($this->trans('Code', [], 'Modules.Pitticafeed.Admin'))
                    ->setOptions([
                        'field'     => 'has_code',
                        'clickable' => false,
                    ])
            )
            ->add(
                (new StatusColumn('has_image'))
                    ->setName($this->trans('Image', [], 'Modules.Pitticafeed.Admin'))
                    ->setOptions([
                        'field'     => 'has_image',
                        'clickable' => false,
                    ])
            )
            ->add(
                (new StatusColumn('has_categories'))
                    ->setName($this->trans('Categories', [], 'Modules.Pitticafeed.Admin'))
                    ->setOptions([
                        'field'     => 'has_categories',
                        'clickable' => false,
                    ])
            );

        if ($this->_isAllShopContext) {
            $columns
                ->add(
                    (new DataColumn('shop_name'))
                        ->setName($this->trans('Shop', [], 'Modules.Pitticafeed.Admin'))
                        ->setOptions(['field' => 'shop_name'])
                );
        }

        return $columns
            ->add(
                (new ToggleColumn('active'))
                    ->setName($this->trans('Active', [], 'Modules.Pitticafeed.Admin'))
                    ->setOptions(
                        [
                            'field'            => 'active',
                            'primary_field'    => 'k',
                            'route'            => 'pittica.prestashop.module.feed.check.toggle',
                            'route_param_name' => 'id',
                        ]
                    )
            )
            ->add(
                (new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Actions'))
                    ->setOptions([
                        'actions' => (new RowActionCollection)
                            ->add((new LinkRowAction('edit'))
                                ->setIcon('edit')
                                ->setName($this->trans('Edit', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route'             => 'admin_product_form',
                                    'route_param_name'  => 'id',
                                    'route_param_field' => 'id_product',
                                ])
                            )
                            ->add((new LinkRowAction('delete'))
                                ->setIcon('delete')
                                ->setName($this->trans('Delete', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'pittica.prestashop.module.feed.check.delete',
                                    'route_param_name' => 'key',
                                    'route_param_field' => 'k',
                                    'confirm_message' => $this->trans('Delete selected items?', [], 'Admin.Actions'),
                                ])
                            )
                    ])
            );
    }

    /**
     * {@inheritDoc}
     *
     * @return FilterCollection
     * @since  1.0.0
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_product', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('ID', [], 'Admin.Global'),
                        ],
                    ])
                    ->setAssociatedColumn('id_product')
            )
            ->add(
                (new Filter('name', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Name', [], 'Modules.Pitticafeed.Admin'),
                        ],
                    ])
                    ->setAssociatedColumn('name')
            )
            ->add(
                (new Filter('has_code', YesAndNoChoiceType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Code', [], 'Modules.Pitticafeed.Admin'),
                        ],
                    ])
                    ->setAssociatedColumn('has_code')
            )
            ->add(
                (new Filter('has_image', YesAndNoChoiceType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Image', [], 'Modules.Pitticafeed.Admin'),
                        ],
                    ])
                    ->setAssociatedColumn('has_image')
            )
            ->add(
                (new Filter('has_categories', YesAndNoChoiceType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Categories', [], 'Modules.Pitticafeed.Admin'),
                        ],
                    ])
                    ->setAssociatedColumn('has_categories')
            )
            ->add(
                (new Filter('active', YesAndNoChoiceType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Active', [], 'Modules.Pitticafeed.Admin'),
                        ],
                    ])
                    ->setAssociatedColumn('active')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'pittica.prestashop.module.feed.check',
                    ])
                    ->setAssociatedColumn('actions')
            );
    }
}
