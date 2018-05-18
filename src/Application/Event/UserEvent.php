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

namespace App\Application\Event;

use App\Domain\Event\Interfaces\UserEventInterface;
use App\Domain\Models\Interfaces\UserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEvent.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class UserEvent extends Event implements UserEventInterface
{
    const USER_ASK_RESET_PASSWORD = 'user.ask_reset_password';

    const USER_CREATED = 'user.created';

    const USER_RESET_PASSWORD = 'user.reset_password';

    const USER_VALIDATED = 'user.validated';

    /**
     * @var string
     */
    private $locale;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $locale,
        UserInterface $user
    ) {
        $this->locale = $locale;
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
