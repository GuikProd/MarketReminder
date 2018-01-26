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

namespace App\Action\Security;

use App\Responder\Security\RegisterResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;

/**
 * Class RegisterAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactoryInterface;

    /**
     * @var RegisterTypeHandlerInterface
     */
    private $registerTypeHandlerInterface;

    public function __invoke(Request $request, RegisterResponder $responder)
    {

    }
}
