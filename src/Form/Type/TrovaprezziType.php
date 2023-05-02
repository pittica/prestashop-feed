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
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;

/**
 * TrovaPrezzi type.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Form/Type/TrovaprezziType.php
 * @since    1.0.0
 */
class TrovaprezziType extends TranslatorAwareType
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
        $builder
            ->add(
                'active',
                SwitchType::class,
                [
                    'label' => $this->trans('Active', 'Modules.Pitticafeed.Admin'),
                ]
            )
            ->add(
                'url',
                UrlType::class,
                [
                    'label'    => $this->trans('Shop URL', 'Modules.Pitticafeed.Admin'),
                    'required' => false
                ]
            )
            ->add(
                'trustedprogram',
                TextType::class,
                [
                    'label'    => $this->trans('TrustedProgram Key', 'Modules.Pitticafeed.Admin'),
                    'required' => false,
                ]
            );
    }
}
