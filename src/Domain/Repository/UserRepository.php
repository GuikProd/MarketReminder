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

namespace App\Domain\Repository;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('user')
                    ->where('user.username = :username OR user.email = :email')
                    ->setParameter('username', $username)
                    ->setParameter('email', $username)
                    ->setCacheable(true)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserByUsernameAndEmail(string $username, string $email): ? UserInterface
    {
        return $this->createQueryBuilder('user')
                    ->where('user.username = :username AND user.email = :email')
                    ->setParameter('username', $username)
                    ->setParameter('email', $email)
                    ->setCacheable(true)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserByUsername(string $username):? UserInterface
    {
        return $this->createQueryBuilder('user')
                    ->where('user.username = :username')
                    ->setParameter('username', $username)
                    ->setCacheable(true)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserByEmail(string $email):? UserInterface
    {
        return $this->createQueryBuilder('user')
                    ->where('user.email = :email')
                    ->setParameter('email', $email)
                    ->setCacheable(true)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserByToken(string $token):? UserInterface
    {
        return $this->createQueryBuilder('user')
                    ->where('user.validationToken = :token AND user.validated = false')
                    ->setParameter('token', $token)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function save(UserInterface $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
