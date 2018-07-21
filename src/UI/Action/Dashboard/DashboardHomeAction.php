<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\UI\Action\Dashboard;

use App\UI\Action\Dashboard\Interfaces\DashboardHomeActionInterface;
use App\UI\Responder\Dashboard\Interfaces\DashboardHomeResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardHomeAction
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     path="/dashboard",
 *     name="dashboard_home",
 *     methods={"GET"}
 * )
 *
 * @Security("has_role('ROLE_USER')")
 */
final class DashboardHomeAction implements DashboardHomeActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        DashboardHomeResponderInterface $responder
    ): Response {

        return $responder($request);
    }
}
