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
use App\Builder\Interfaces\UserBuilderInterface;

/**
 * Interface RegisterTypeHandlerInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisterTypeHandlerInterface
{
    /**
     * @param FormInterface        $registerForm    The RegisterType form.
     * @param UserBuilderInterface $userBuilder     The User which is managed by the form.
     *
     * @return bool                                 If the handling process has succeed.
     */
    public function handle(FormInterface $registerForm, UserBuilderInterface $userBuilder): bool;
}