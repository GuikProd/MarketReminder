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

namespace App\Tests\UI\Responder\Security;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordResponderUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordResponderUnitTest extends TestCase
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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
        $this->form = $this->createMock(FormInterface::class);
        $this->redisTranslationRepository = $this->createMock(RedisTranslationRepositoryInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');

        $this->presenter = new Presenter($this->redisTranslationRepository);
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
            $resetPasswordResponder(true)
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
            $resetPasswordResponder(false, $this->form)
        );
    }
}
