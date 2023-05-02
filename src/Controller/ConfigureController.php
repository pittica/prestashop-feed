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

namespace Pittica\PrestaShop\Module\Feed\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

/**
 * General configuration controller.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Controller/ConfigureController.php
 * @since    1.0.0
 */
class ConfigureController extends FrameworkBundleAdminController
{
    /**
     * Handles "index" request.
     *
     * @param Request $request HTTP request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function index(Request $request) : Response
    {
        $handler = $this->get('pittica.prestashop.modules.feed.form.configure.data_handler');
        $form    = $handler->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $handler->save($form->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('pittica.prestashop.module.feed.configure.general');
            }

            $this->flashErrors($errors);
        }

        $locator = $this->get('pittica.prestashop.module.feed.tools.locator');
        
        return $this->render(
            '@Modules/pitticafeed/views/templates/admin/configure.html.twig',
            [
                'generalForm'            => $form->createView(),
                'layoutTitle'            => $this->trans('Settings', 'Modules.Pitticafeed.Admin'),
                'icon'                   => 'list',
                'formName'               => $this->trans('Offers', 'Modules.Pitticafeed.Admin'),
                'current'                => 'pittica.prestashop.module.feed.configure.general',
                'generatorUrl'           => $locator->getGeneratorUrl(),
                'cli'                    => $locator->getCli(),
                'layoutHeaderToolbarBtn' => [
                    'update' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.configure.general.update'),
                        'desc' => $this->trans('Update Feeds', 'Modules.Pitticafeed.Admin'),
                        'icon' => 'sync'
                    ],
                    'check' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.check'),
                        'desc' => $this->trans('Check Products', 'Modules.Pitticafeed.Admin'),
                        'icon' => 'check'
                    ]
                ]
            ]
        );
    }

    /**
     * Handles "update" request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function update() : Response
    {
        $google      = $this->get('pittica.prestashop.module.feed.providers.google');
        $trovaprezzi = $this->get('pittica.prestashop.module.feed.providers.trovaprezzi');
            
        if ($google->generate() && $trovaprezzi->generate()) {
            $this->addFlash('success', $this->trans('Documents updated.', 'Modules.Pitticafeed.Admin'));

            return $this->redirectToRoute('pittica.prestashop.module.feed.configure.general');
        } else {
            $this->addFlash('error', $this->trans('Documents update failed.', 'Modules.Pitticafeed.Admin'));
        }

        return $this->redirectToRoute('pittica.prestashop.module.feed.configure.general');
    }
}
