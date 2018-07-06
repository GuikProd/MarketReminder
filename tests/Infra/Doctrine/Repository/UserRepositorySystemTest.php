<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\Doctrine\Repository;

use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserRepositorySystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserRepositorySystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->userRepository = static::$kernel->getContainer()
                                               ->get('doctrine.orm.entity_manager')
                                               ->getRepository(User::class);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testUserIsNotFoundWithWrongUsername()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1MB', 'Repository user not found memory usage');
        $configuration->assert('metrics.sql.queries.count == 1', 'Repository user not found SQL queries');
        $configuration->assert('main.network_in < 4kB', 'Repository user not found network in');
        $configuration->assert('main.network_out < 110B', 'Repository user not found network out');

        $this->assertBlackfire($configuration, function () {
            $this->userRepository->getUserByUsername('hthth');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testUserIsFoundUsingGoodUsername()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 100kB', 'Repository user found memory usage');
        $configuration->assert('metrics.sql.queries.count == 1', 'Repository user found SQL queries');
        $configuration->assert('main.network_in < 4kB', 'Repository user found network in');
        $configuration->assert('main.network_out < 110B', 'Repository user found network out');

        $this->assertBlackfire($configuration, function () {
            $this->userRepository->getUserByUsername('Toto');
        });
    }
}
