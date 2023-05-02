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

namespace Pittica\PrestaShop\Module\Feed\Form\Type;

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Shopalike type.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/Type/ShopalikeType.php
 * @since    1.0.0
 */
class ShopalikeType extends TranslatorAwareType
{
    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array                $options Options.
     *
     * @return void
     * @since  1.0.0
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $container  = SymfonyContainer::getInstance();
        $employee   = $container
            ->get('prestashop.adapter.data_provider.employee')
            ->getData();
        $dbPrefix   = $container->getParameter('database_prefix');
        $shop       = $container
            ->get('prestashop.adapter.shop.context')
            ->getContextShopID(true);
        $attributes = [
            $this->trans('None', 'Admin.Global') => 0
        ];
        $qb         = $container
            ->get('doctrine.orm.entity_manager')
            ->getConnection()
            ->createQueryBuilder()
            ->from(
                $dbPrefix . 'attribute_group',
                'ag'
            )
            ->select(
                [
                    'CONCAT(ag.id_attribute_group, " - ", agl.public_name) AS name',
                    'ag.id_attribute_group AS id',
                ]
            )
            ->setParameter('lang', (int) $employee['languageId'])
            ->leftJoin(
                'ag',
                $dbPrefix . 'attribute_group_lang',
                'agl',
                'agl.id_attribute_group = ag.id_attribute_group AND agl.id_lang = :lang'
            )
            ->groupBy('ag.id_attribute_group')
            ->orderBy('ag.position', 'ASC');

        if ($shop) {
            $qb
                ->setParameter('shop', $shop)
                ->innerJoin(
                    'ags',
                    $this->_dbPrefix . 'attribute_group_shop',
                    'ag',
                    's.id_shop = ags.id_shop'
                )
                ->innerJoin(
                    's',
                    $this->_dbPrefix . 'shop',
                    'ags',
                    'ags.id_shop = s.id_shop AND s.id_shop = :shop'
                );
        }
        
        $results = $qb
            ->execute()
            ->fetchAll();
        
        foreach ($results as $result) {
            $attributes[$result['name']] = (int) $result['id'];
        }

        $builder
            ->add(
                'active',
                SwitchType::class,
                [
                    'label' => $this->trans('Active', 'Modules.Pitticafeed.Admin'),
                ]
            )
            ->add(
                'color',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Color', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'size',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Size', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Gender', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'material',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Material', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'age',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Age', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'energy_consumption',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Energy Consumption', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            )
            ->add(
                'heel_height',
                ChoiceType::class,
                [
                    'label'   => $this->trans('Heel Height', 'Modules.Pitticafeed.Admin'),
                    'choices' => $attributes
                ]
            );
    }
}
