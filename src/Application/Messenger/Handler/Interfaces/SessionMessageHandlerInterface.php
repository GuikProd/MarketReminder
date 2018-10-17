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

namespace App\Application\Messenger\Handler\Interfaces;

use App\Application\Messenger\Message\SessionMessage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface SessionMessageHandlerInterface.
 *
 * @package App\Application\Messenger\Handler\Interfaces
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface SessionMessageHandlerInterface
{
    /**
     * SessionMessageHandlerInterface constructor.
     *
     * @param SessionInterface    $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator
    );

    /**
     * @param SessionMessage $message
     *
     * @return void
     */
    public function __invoke(SessionMessage $message): void;
}
