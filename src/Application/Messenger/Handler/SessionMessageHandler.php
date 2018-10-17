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

namespace App\Application\Messenger\Handler;

use App\Application\Messenger\Handler\Interfaces\SessionMessageHandlerInterface;
use App\Application\Messenger\Message\SessionMessage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SessionMessageHandler.
 *
 * @package App\Application\Messenger\Handler
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageHandler implements SessionMessageHandlerInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @inheritdoc
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(SessionMessage $message): void
    {
        $this->session->getFlashBag()->add(
            $message->getKey(),
            $this->translator->trans($message->getMessage(), [], 'session')
        );
    }
}
