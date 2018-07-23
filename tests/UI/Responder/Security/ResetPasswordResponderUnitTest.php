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

namespace App\Tests\UI\Responder\Security;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ResetPasswordResponderUnitTest extends TestCase
{
    /**
     * @var FormInterface|null
     */
    private $form = null;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var CloudTranslationRepositoryInterface|null
     */
    private $cloudTranslationRepository = null;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * @var UrlGeneratorInterface|null
     */
    private $urlGenerator = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->form = $this->createMock(FormInterface::class);
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
        $this->request = Request::create('/fr/reset-password', 'GET');
        $this->request->attributes->set('_locale', 'fr');
    }

    public function testItImplements()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            ResetPasswordResponderInterface::class,
            $resetPasswordResponder
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testItReturnARedirectResponse()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordResponder($this->request, true)
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testItReturnAResponse()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $resetPasswordResponder($this->request, false, $this->form)
        );
    }
}
