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

namespace App\UI\Responder\Core\Interfaces;

use App\UI\Presenter\Interfaces\PresenterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Zend\Diactoros\Response;

/**
 * Interface HomeResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface HomeResponderInterface
{
    /**
     * HomeResponderInterface constructor.
     *
     * @param Environment        $twig
     * @param PresenterInterface $presenter
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter
    );

    /**
     * @param ServerRequestInterface $request
     *
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request): Response;
}
