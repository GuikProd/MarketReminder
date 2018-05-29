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

namespace App\Tests\UI\Action\Security;

use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use App\UI\Responder\Security\AskResetPasswordResponder;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class AskResetPasswordResponderUnitTest extends TestCase
{
    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->presenter = new Presenter($this->redisTranslationRepository);
        $this->request = $this->createMock(Request::class);

        $this->request->method('getLocale')->willReturn('fr');
        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            AskResetPasswordResponderInterface::class,
            $askResetPasswordResponder
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponseIsReturned()
    {
        $formInterface = $this->createMock(FormInterface::class);

        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordResponder(false, $this->request, $formInterface)
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testRedirectResponseIsReturned()
    {
        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordResponder(true, $this->request)
        );
    }
}
