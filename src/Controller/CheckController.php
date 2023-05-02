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
use Pittica\PrestaShop\Module\Feed\Search\Filters\OfferFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

/**
 * Check controller.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Controller/CheckController.php
 * @since    1.0.0
 */
class CheckController extends FrameworkBundleAdminController
{
    /**
     * Handles "index" request.
     *
     * @param OfferFilters $filters Serach filters.
     *
     * @return Response
     * @since  1.0.0
     */
    public function index(OfferFilters $filters) : Response
    {
        $factory   = $this->get('pittica.prestashop.modules.feed.factory.offers');
        $quoteGrid = $factory->getGrid($filters);

        return $this->render(
            '@Modules/pitticafeed/views/templates/admin/check.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle'   => $this->trans('Offers', 'Modules.Pitticafeed.Admin'),
                'quoteGrid'     => $this->presentGrid($quoteGrid),
                'layoutHeaderToolbarBtn' => [
                    'update' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.check.update'),
                        'desc' => $this->trans('Update', 'Admin.Global'),
                        'icon' => 'sync'
                    ],
                    'rebuild' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.check.rebuild'),
                        'desc' => $this->trans('Rebuild documents', 'Modules.Pitticafeed.Admin'),
                        'icon' => 'sync_alt'
                    ],
                    'settings' => [
                        'href' => $this->generateUrl('pittica.prestashop.module.feed.configure.general'),
                        'desc' => $this->trans('Settings', 'Admin.Global'),
                        'icon' => 'settings'
                    ]
                ]
            ]
        );
    }

    /**
     * Handles "toggle" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function toggle(Request $request) : Response
    {
        $id = explode('-', $request->query->get('id'), 3);
        
        if (count($id) === 3) {
            $entity = $this
                ->get('pittica.prestashop.module.feed.repository.offer')
                ->findOneBy(
                    [
                        'productId'          => $id[0],
                        'productAttributeId' => $id[1],
                        'shopId'             => $id[2],
                    ]
                );
            
            if ($entity !== null) {
                $entity->toggleActive();

                $this
                    ->get('doctrine.orm.entity_manager')
                    ->flush();
            }
        }
        
        return $this->redirectToRoute('pittica.prestashop.module.feed.check');
    }

    /**
     * Handles "search" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function search(Request $request) : Response
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('pittica.prestashop.modules.feed.definition.factory.offer'),
            $request,
            'offer',
            'pittica.prestashop.module.feed.check'
        );
    }

    /**
     * Handles "update" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function update(Request $request) : Response
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');
        $updater         = $this
            ->get('pittica.prestashop.module.feed.tools.updater');

        if ($updater->updateProducts()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
        } else {
            $this->addFlash('error', $this->trans('Update failed.', 'Modules.Pitticafeed.Admin'));
        }

        return $responseBuilder->buildSearchResponse(
            $this->get('pittica.prestashop.modules.feed.definition.factory.offer'),
            $request,
            'offer',
            'pittica.prestashop.module.feed.check'
        );
    }

    /**
     * Handles "rebuild" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function rebuild(Request $request) : Response
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');
        $updater         = $this
            ->get('pittica.prestashop.module.feed.tools.updater');

        if ($updater->generate(false)) {
            $this->addFlash('success', $this->trans('Feeds documents have been updated.', 'Modules.Pitticafeed.Admin'));
        } else {
            $this->addFlash('error', $this->trans('Update failed.', 'Modules.Pitticafeed.Admin'));
        }

        return $responseBuilder->buildSearchResponse(
            $this->get('pittica.prestashop.modules.feed.definition.factory.offer'),
            $request,
            'offer',
            'pittica.prestashop.module.feed.check'
        );
    }

    /**
     * Handles "delete" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function delete(Request $request) : Response
    {
        $id = explode('-', $request->query->get('key'), 3);
        
        if (count($id) === 3) {
            $entity = $this
                ->get('pittica.prestashop.module.feed.repository.offer')
                ->findOneBy(
                    [
                        'productId'          => $id[0],
                        'productAttributeId' => $id[1],
                        'shopId'             => $id[2],
                    ]
                );
            
            if ($entity !== null) {
                $em = $this
                    ->get('doctrine.orm.entity_manager');
    
                $em->remove($entity);

                $em
                    ->flush();
            }
        }

        return $this->redirectToRoute('pittica.prestashop.module.feed.check');
    }

    /**
     * Handles "enableSelection" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function enableSelection(Request $request) : Response
    {
        return $this->_bulkSelection($request, true);
    }

    /**
     * Handles "disableSelection" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function disableSelection(Request $request) : Response
    {
        return $this->_bulkSelection($request, false);
    }

    /**
     * Handles "deleteSelection" request.
     *
     * @param Request $request Request.
     *
     * @return Response
     * @since  1.0.0
     */
    public function deleteSelection(Request $request) : Response
    {
        $entries = $request->request->get('offer_bulk_action');

        if (is_array($entries)) {
            $em = $this
                ->get('doctrine.orm.entity_manager');

            foreach ($entries as $entry) {
                $id = explode('-', $entry, 3);
        
                if (count($id) === 3) {
                    $entity = $this
                        ->get('pittica.prestashop.module.feed.repository.offer')
                        ->findOneBy(
                            [
                                'productId'          => $id[0],
                                'productAttributeId' => $id[1],
                                'shopId'             => $id[2],
                            ]
                        );
            
                    if ($entity !== null) {
                        $em->remove($entity);

                        $em
                            ->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('pittica.prestashop.module.feed.check');
    }

    /**
     * Handles a bulk action.
     *
     * @param Request $request Request.
     * @param boolean $value   A value indicating the target is enabled or disabled.
     *
     * @return Response
     * @since  1.0.0
     */
    private function _bulkSelection(Request $request, bool $value = false) : Response
    {
        $entries = $request->request->get('offer_bulk_action');

        if (is_array($entries)) {
            foreach ($entries as $entry) {
                $id = explode('-', $entry, 3);
        
                if (count($id) === 3) {
                    $entity = $this
                        ->get('pittica.prestashop.module.feed.repository.offer')
                        ->findOneBy(
                            [
                                'productId'          => $id[0],
                                'productAttributeId' => $id[1],
                                'shopId'             => $id[2],
                            ]
                        );
            
                    if ($entity !== null) {
                        $entity->setActive($value);

                        $this
                            ->get('doctrine.orm.entity_manager')
                            ->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('pittica.prestashop.module.feed.check');
    }
}
