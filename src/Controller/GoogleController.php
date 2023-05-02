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
 * Google configuration controller.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Controller/GoogleController.php
 * @since    1.0.0
 */
class GoogleController extends FrameworkBundleAdminController
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
        $handler = $this->get('pittica.prestashop.modules.feed.form.google.data_handler');
        $form    = $handler->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $handler->save($form->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('pittica.prestashop.module.feed.configure.google');
            }

            $this->flashErrors($errors);
        }

        $locator = $this->get('pittica.prestashop.module.feed.tools.locator');

        return $this->render(
            '@Modules/pitticafeed/views/templates/admin/feeds.html.twig',
            [
                'generalForm'            => $form->createView(),
                'layoutTitle'            => $this->trans('Google', 'Modules.Pitticafeed.Admin'),
                'icon'                   => 'settings',
                'formName'               => $this->trans('Settings', 'Modules.Pitticafeed.Admin'),
                'current'                => 'pittica.prestashop.module.feed.configure.google',
                'downloadUrl'            => $locator->getDownloadUrl('google'),
                'generatorUrl'           => $locator->getGeneratorUrl('google'),
                'cli'                    => $locator->getCli('google'),
                'layoutHeaderToolbarBtn' => [
                    'update' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.configure.google.update'),
                        'desc' => $this->trans('Update Feed', 'Modules.Pitticafeed.Admin'),
                        'icon' => 'sync'
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
        $provider = $this->get('pittica.prestashop.module.feed.providers.google');
            
        if ($provider->generate()) {
            $this->addFlash('success', $this->trans('Google updated.', 'Modules.Pitticafeed.Admin'));

            return $this->redirectToRoute('pittica.prestashop.module.feed.configure.google');
        } else {
            $this->addFlash('error', $this->trans('Google update failed.', 'Modules.Pitticafeed.Admin'));
        }

        return $this->redirectToRoute('pittica.prestashop.module.feed.configure.google');
    }
}
