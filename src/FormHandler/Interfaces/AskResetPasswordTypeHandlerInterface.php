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

namespace App\FormHandler\Interfaces;

use Symfony\Component\Form\FormInterface;

/**
 * Interface AskResetPasswordTypeHandlerInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface AskResetPasswordTypeHandlerInterface
{
    /**
     * @param FormInterface $askResetPasswordType
     *
     * @return bool
     */
    public function handle(FormInterface $askResetPasswordType): bool;
}
