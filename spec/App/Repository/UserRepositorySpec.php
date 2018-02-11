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

namespace spec\App\Repository;

use PhpSpec\ObjectBehavior;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepositorySpec;.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRepositorySpec extends ObjectBehavior
{
    /**
     * @param EntityManagerInterface|\PhpSpec\Wrapper\Collaborator $entityManager
     * @param ClassMetadata|\PhpSpec\Wrapper\Collaborator          $classMetadata
     */
    public function it_implements(
        EntityManagerInterface $entityManager,
        ClassMetadata $classMetadata
    ) {
        $this->beConstructedWith($entityManager, $classMetadata);
        $this->shouldImplement(UserRepositoryInterface::class);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param ClassMetadata          $classMetadata
     */
    public function should_return_null(
        EntityManagerInterface $entityManager,
        ClassMetadata $classMetadata
    ) {
        $this->beConstructedWith($entityManager, $classMetadata);
        $this->getUserByEmail('Toto@gmail.com')->shouldReturn(null);
    }
}
