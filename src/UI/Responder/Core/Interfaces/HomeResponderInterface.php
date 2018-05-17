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

namespace App\UI\Responder\Core\Interfaces;

use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Interface HomeResponderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
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
     * @param Request $request
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function __invoke(Request $request);
}
