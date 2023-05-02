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

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Feed exporter main class.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/pitticafeed.php
 * @since    1.0.0
 */
class PitticaFeed extends Module
{
    /**
     * {@inheritDoc}
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->name          = 'pitticafeed';
        $this->tab           = 'market_place';
        $this->version       = '1.0.0';
        $this->author        = 'Pittica';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('Feed', [], 'Modules.Pitticafeed.Admin');
        $this->description = $this->trans('Feed generator and exporter.', [], 'Modules.Pitticafeed.Admin');

        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return boolean
     * @since  1.0.0
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $installer = new \Pittica\PrestaShop\Module\Feed\Install\Installer(
            $this,
            $this->get('prestashop.adapter.legacy.configuration'),
            $this->getContainer()->getParameter('database_prefix'),
            $this->get('doctrine.orm.entity_manager')
        );

        return parent::install() && $installer->install();
    }

    /**
     * {@inheritDoc}
     *
     * @return boolean
     * @since  1.0.0
     */
    public function uninstall()
    {
        return $this->get('pittica.prestashop.module.feed.installer')->uninstall() && parent::uninstall();
    }
    
    /**
     * {@inheritDoc}
     *
     * @return string
     * @since  1.0.0
     */
    public function getContent()
    {
        return Tools::redirectAdmin(
            $this
                ->get('router')
                ->generate('pittica.prestashop.module.feed.configure.general')
        );
    }

    /**
     * Handles the "displayFooterAfter" hook.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function hookDisplayFooterAfter() : ?string
    {
        $configuration = $this->get('prestashop.adapter.legacy.configuration');

        $this->smarty->assign(
            [
                'trovaprezziActive' => $configuration->getBoolean('PITTICA_FEED_TROVAPREZZI_ACTIVE'),
                'trovaprezziUrl'    => $configuration->get('PITTICA_FEED_TROVAPREZZI_URL')
            ]
        );

        return $this->fetch('module:' . $this->name . '/views/templates/hook/displayFooterAfter.tpl');
    }

    /**
     * Handles the "actionAdminControllerSetMedia" hook.
     *
     * @return void
     * @since  1.0.0
     */
    public function hookActionAdminControllerSetMedia() : void
    {
        if (!empty($this->context->controller)) {
            if (preg_match('/^\/modules\/' . $this->name . '\/check/', $this->get('router.request_context')->getPathInfo())) {
                $this->context->controller->addJS($this->getPathUri() . 'views/js/check.js');
            }
        
            if (preg_match('/^\/modules\/' . $this->name . '\/configure/', $this->get('router.request_context')->getPathInfo())) {
                $this->context->controller->addCSS($this->getPathUri() . 'views/css/fonts.css');
            }
        }
    }

    /**
     * Handles the "actionFrontControllerSetMedia" hook.
     *
     * @return void
     * @since  1.0.0
     */
    public function hookActionFrontControllerSetMedia() : void
    {
        $configuration = $this->get('prestashop.adapter.legacy.configuration');
        
        if ($configuration->getBoolean('PITTICA_FEED_TROVAPREZZI_ACTIVE') && !empty($configuration->get('PITTICA_FEED_TROVAPREZZI_TRUSTEDPROGRAM'))) {
            $this->context->controller->registerJavascript(
                'trovaprezzi',
                'https://tracking.trovaprezzi.it/javascripts/tracking-vanilla.min.js',
                ['server' => 'remote', 'position' => 'head', 'priority' => 20]
            );
            
            if ($this->context->controller->php_self === 'order-confirmation') {
                $this->context->controller->registerJavascript(
                    'trovaprezzi-trustedprogram',
                    'modules/' . $this->name . '/views/js/trovaprezzi-trustedprogram.js',
                    [
                        'priority' => 200,
                        'attribute' => 'async',
                    ]
                );
            }
        }
    }

    /**
     * Handles the "displayOrderConfirmation" hook.
     *
     * @param array $hookParams Hook parameters.
     *
     * @return string
     * @since  1.0.0
     */
    public function hookDisplayOrderConfirmation(array $hookParams) : string
    {
        if ($this->context->controller->php_self === 'order-confirmation' && !empty($hookParams['order'])) {
            $configuration = $this->get('prestashop.adapter.legacy.configuration');
        
            if ($configuration->getBoolean('PITTICA_FEED_TROVAPREZZI_ACTIVE')) {
                $key = $configuration->get('PITTICA_FEED_TROVAPREZZI_TRUSTEDPROGRAM');

                if (!empty($key)) {
                    $order    = $hookParams['order'];
                    $products = $order->getProducts();
                    $vars     = [
                        'trovaprezzi' => [
                            'trustedprogram' => [
                                'key'   => $key,
                                'order' => [
                                    'id'       => $order->id,
                                    'email'    => $this->context->customer->email,
                                    'total'    => number_format((float) $order->total_paid, 2, '.', ''),
                                    'products' => [],
                                ],
                            ],
                        ],
                    ];

                    foreach ($products as $product) {
                        $vars['trovaprezzi']['trustedprogram']['order']['products'][] = [
                            'sku'  => !empty($product['product_reference']) ? $product['product_reference'] : $product['ean13'],
                            'name' => $product['product_name']
                        ];
                    }

                    Media::addJsDef($vars);
                }
            }
        }

        return '';
    }
}
