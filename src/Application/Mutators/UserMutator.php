<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Mutators;

use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class UserMutator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserMutator implements MutationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * UserMutator constructor.
     *
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function register(Argument $argument)
    {
    }

    public function login(array $credentials)
    {
    }

    public function forgotPassword(array $credentials)
    {
    }

    public function dropUser(array $credentials)
    {
    }
}
