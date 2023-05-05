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
use Symfony\Component\Translation\TranslatorInterface;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
use PrestaShop\PrestaShop\Adapter\Carrier\CarrierDataProvider;

/**
 * Configure type.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/Type/ConfigureType.php
 * @since    1.0.0
 */
class ConfigureType extends TranslatorAwareType
{
    /**
     * A value indicating whether the shop is in current context.
     *
     * @var boolean
     */
    private $_isSingleShop;

    /**
     * Carrier data provider.
     *
     * @var [type]
     */
    private $_carriers;

    /**
     * {@inheritDoc}
     *
     * @param TranslatorInterface $translator   Translator.
     * @param array               $locales      Locales.
     * @param boolean             $isSingleShop A value indicating whether the shop is in current context.
     * @param CarrierDataProvider $carriers     Carrier data provider.
     */
    public function __construct(TranslatorInterface $translator, array $locales, bool $isSingleShop, CarrierDataProvider $carriers)
    {
        parent::__construct($translator, $locales);

        $this->_isSingleShop = $isSingleShop;
        $this->_carriers     = $carriers;
    }

    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array                $options Options.
     *
     * @return void
     * @since  1.0.0
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'skip_empty',
                SwitchType::class,
                [
                    'label'    => $this->trans('Skip empty quantities', 'Modules.Pitticafeed.Admin'),
                    'required' => false,
                ]
            )
            ->add(
                'only_active',
                SwitchType::class,
                [
                    'label'    => $this->trans('Only active products', 'Modules.Pitticafeed.Admin'),
                    'required' => false,
                    'help'     => $this->trans('Saves memory on generation actions.', 'Modules.Pitticafeed.Admin'),
                ]
            )
            ->add(
                'excluded_categories',
                CategoryChoiceTreeType::class,
                [
                    'label'    => $this->trans('Excluded categories', 'Modules.Pitticafeed.Admin'),
                    'required' => false,
                    'multiple' => true,
                ]
            );

        if ($this->_isSingleShop) {
            $builder
                ->add(
                    'carrier',
                    ChoiceType::class,
                    [
                        'label'   => $this->trans('Carrier', 'Modules.Pitticafeed.Admin'),
                        'choices' => $this->_carriers->getActiveCarriersChoices(),
                    ]
                );
        }
    }
}
