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

namespace App\Tests\Application\Command;

use App\Application\Command\SessionCleanerCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class SessionCleanerCommandTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionCleanerCommandTest extends KernelTestCase
{
    /**
     * @var KernelInterface
     */
    private $commandKernel;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->commandKernel = static::bootKernel();
    }

    public function testSessionsIsCleaned()
    {
        $application = new Application($this->commandKernel);
        $application->add(new SessionCleanerCommand());

        $command = $application->find('app:session-cleaner');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'lifetime' => 6000
        ]);
    }
}
