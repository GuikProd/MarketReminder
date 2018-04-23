<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Command;

use App\Application\Command\Interfaces\SessionCleanerCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SessionCleanerCommand.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionCleanerCommand extends Command implements SessionCleanerCommandInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:session-cleaner')
            ->setHelp('This command allow to clean the session garbage')
            ->addArgument(
                'lifetime',
                InputArgument::REQUIRED,
                'The seconds since the sessions hasn\'t been updated.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sessionHandler = new \SessionHandler();
        $sessionHandler->gc($input->getArgument('lifetime'));
    }
}
