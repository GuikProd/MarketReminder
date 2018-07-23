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

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserRepositoryTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testInterfaceImplementation()
    {
        static::assertInstanceOf(
            UserRepositoryInterface::class,
            $this->entityManager->getRepository(User::class)
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserIsLoadedUsingUsername()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(User::class)
                                ->loadUserByUsername('Toto')
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserIsLoadedUsingEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(User::class)
                                ->loadUserByUsername('toto@gmail.com')
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserIsNotLoadedUsingUsername()
    {
        static::assertNull(
            $this->entityManager->getRepository(User::class)
                                ->loadUserByUsername('Tutu')
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserIsNotLoadedUsingEmail()
    {
        static::assertNull(
            $this->entityManager->getRepository(User::class)
                                ->loadUserByUsername('tutu@gmail.com')
        );
    }

    public function testUserIsNotFoundByUsernameAndEmail()
    {
        static::assertNull(
            $this->entityManager->getRepository(User::class)
                                ->getUserByUsernameAndEmail('Titi', 'titi@gmail.com')
        );
    }

    public function testUserIsFoundByUsernameAndEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(User::class)
                                ->getUserByUsernameAndEmail('Toto', 'toto@gmail.com')
        );
    }

    public function testUserIsReturnedByUsername()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(User::class)
                                ->getUserByUsername('Toto')
        );
    }

    public function testUserIsReturnedByEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(User::class)
                                ->getUserByEmail('hp@gmail.com')
        );
    }
}
