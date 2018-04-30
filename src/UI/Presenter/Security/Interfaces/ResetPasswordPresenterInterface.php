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

namespace App\UI\Presenter\Security\Interfaces;

use Symfony\Component\Form\FormView;

/**
 * Interface ResetPasswordPresenterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ResetPasswordPresenterInterface
{
    /**
     * @return null|FormView
     */
    public function getForm(): ?FormView;

    /**
     * @return array
     */
    public function getNotificationMessage(): array;
}
