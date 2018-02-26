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

namespace App\Action\Dashboard;

use Symfony\Component\Routing\Annotation\Route;
use App\Responder\Dashboard\DashboardHomeResponder;

/**
 * Class DashboardHomeAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     path="/{_locale}/dashboard",
 *     name="dashboard_home",
 *     methods={"GET"},
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
class DashboardHomeAction
{
    public function __invoke(DashboardHomeResponder $responder)
    {
        return $responder();
    }
}
