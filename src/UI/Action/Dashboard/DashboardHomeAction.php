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

namespace App\UI\Action\Dashboard;

use App\UI\Responder\Dashboard\DashboardHomeResponder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardHomeAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     path="/dashboard",
 *     name="dashboard_home",
 *     methods={"GET"}
 * )
 */
class DashboardHomeAction
{
    public function __invoke(DashboardHomeResponder $responder)
    {
        return $responder();
    }
}
