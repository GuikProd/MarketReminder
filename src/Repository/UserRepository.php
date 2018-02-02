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

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Models\Interfaces\UserInterface;
use App\Repository\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
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
}
