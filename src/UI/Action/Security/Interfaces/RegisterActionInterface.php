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

namespace App\UI\Action\Security\Interfaces;

use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RegisterActionInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisterActionInterface
{
    /**
     * @param Request $request
     * @param RegisterResponderInterface $responder
     *
     * @return mixed
     */
    public function __invoke(
        Request $request,
        RegisterResponderInterface $responder
    );
}
