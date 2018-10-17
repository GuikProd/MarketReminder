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

namespace App\UI\Action\Security\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Responder\Security\Interfaces\ValidationTokenResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Interface ValidationTokenActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ValidationTokenActionInterface
{
    /**
     * ValidationTokenActionInterface constructor.
     *
     * @param MessageBusInterface $messageBus
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $repository
    );

    /**
     * @param Request $request
     * @param ValidationTokenResponderInterface $responder
     * 
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return RedirectResponse
     */
    public function __invoke(
        Request $request,
        ValidationTokenResponderInterface $responder
    ): RedirectResponse;
}
