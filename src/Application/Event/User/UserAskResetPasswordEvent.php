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

namespace App\Application\Event\User;

use App\Application\Event\User\Interfaces\UserAskResetPasswordEventInterface;

/**
 * Class UserAskResetPasswordEvent.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserAskResetPasswordEvent extends AbstractUserEvent implements UserAskResetPasswordEventInterface
{
}