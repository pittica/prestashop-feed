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

namespace Pittica\PrestaShop\Module\Feed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Update CLI command.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Command/UpdateCommand.php
 * @since    1.0.0
 */
class UpdateCommand extends Command
{
    /**
     * {@inheritdoc}
     *
     * @return void
     * @since  1.0.0
     */
    protected function configure() : void
    {
        $this
            ->setName('feed:update')
            ->setDescription('Update commands.')
            ->addArgument('feed', InputArgument::OPTIONAL, 'Command action.');
    }

    /**
     * {@inheritDoc}
     *
     * @param InputInterface  $input  Input interface.
     * @param OutputInterface $output Output interface.
     *
     * @return integer|null Null or 0 if everything went fine, or an error code.
     * @since  1.0.0
     */
    protected function execute(InputInterface $input, OutputInterface $output) : ?int
    {
        $arguments = $input->getArguments();
        $provider  = !empty($arguments['feed']) ? strtolower($arguments['feed']) : null;
        $result    = $this
            ->getService('pittica.prestashop.module.feed.tools.updater')
            ->generate(true, $provider);

        return $result ? 0 : null;
    }
}
