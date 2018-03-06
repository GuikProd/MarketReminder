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

namespace App\UI\Action\Core;

use App\Responder\Core\ContactResponder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="contact",
 *     path="/{_locale}/contact",
 *     methods={"GET", "POST"},
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
class ContactAction
{
    public function __invoke(ContactResponder $responder)
    {
        return $responder();
    }
}
