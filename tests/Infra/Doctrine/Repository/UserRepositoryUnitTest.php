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

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Infra\Doctrine\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepositoryUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserRepositoryUnitTest extends TestCase
{
    public function testInterfaceImplementation()
    {
        $classMetaDataMock = $this->createMock(ClassMetadata::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $registryMock = $this->createMock(RegistryInterface::class);

        $entityManagerMock->method('getClassMetaData')->willReturn($classMetaDataMock);
        $registryMock->method('getManagerForClass')->willReturn($entityManagerMock);

        $repository = new UserRepository($registryMock);

        static::assertInstanceOf(UserRepositoryInterface::class, $repository);
    }
}
