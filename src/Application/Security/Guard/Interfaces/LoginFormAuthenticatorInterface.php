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

use Symfony\Component\Messenger\MessageBusInterface;
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
     * @param MessageBusInterface          $messageBus
     * @param UrlGeneratorInterface        $urlGenerator
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $userPasswordEncoder
    );
}
