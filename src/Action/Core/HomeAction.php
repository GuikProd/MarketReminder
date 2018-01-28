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

namespace App\Action\Core;

use App\Responder\Core\HomeResponder;

/**
 * Class HomeAction.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeAction
{
    /**
     * @param HomeResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(HomeResponder $responder)
    {
        return $responder();
    }
}
