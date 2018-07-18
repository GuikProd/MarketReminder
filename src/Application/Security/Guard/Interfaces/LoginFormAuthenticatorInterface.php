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

namespace App\Application\Security\Guard\Interfaces;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Interface LoginFormAuthenticatorInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface LoginFormAuthenticatorInterface
{
    /**
     * LoginFormAuthenticatorInterface constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $userPasswordEncoder
    );
}
