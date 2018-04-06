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

namespace App\UI\Action\Security;

use App\UI\Action\Security\Interfaces\RegisterActionInterface;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Form\Type\RegisterType;
use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RegisterAction.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="web_registration",
 *     path="/{_locale}/register",
 *     methods={"GET", "POST"},
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
class RegisterAction implements RegisterActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var RegisterTypeHandlerInterface
     */
    private $registerTypeHandler;

    /**
     * RegisterAction constructor.
     *
     * @param FormFactoryInterface         $formFactory
     * @param UrlGeneratorInterface        $urlGenerator
     * @param RegisterTypeHandlerInterface $registerTypeHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        RegisterTypeHandlerInterface $registerTypeHandler
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->registerTypeHandler = $registerTypeHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        RegisterResponderInterface $responder
    ) {
        $registerType = $this->formFactory
                             ->create(RegisterType::class)
                             ->handleRequest($request);

        if ($this->registerTypeHandler->handle($registerType)) {
            return new RedirectResponse(
                $this->urlGenerator->generate('index')
            );
        }

        return $responder($registerType);
    }
}
