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

use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use App\UI\Presenter\Security\ResetPasswordPresenter;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordResponderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ResetPasswordPresenterInterface
     */
    private $resetPasswordPresenter;

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
        $this->resetPasswordPresenter = new ResetPasswordPresenter();
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            ResetPasswordResponderInterface::class,
            $resetPasswordResponder
        );
    }

    /**
     * @doesNotPerformAssertions
     *
     * @group Blackfire
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testBlackfireProfilingAndRedirectResponseIsReturned()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        $probe = static::$blackfire->createProbe();

        $resetPasswordResponder(true);

        static::$blackfire->endProbe($probe);
    }

    /**
     * @doesNotPerformAssertions
     *
     * @group Blackfire
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testBlackfireProfilingAndResponseIsReturned()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        $probe = static::$blackfire->createProbe();

        $resetPasswordResponder(false, $this->form);

        static::$blackfire->endProbe($probe);
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
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordResponder(false)
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
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $resetPasswordResponder(true, $this->form)
        );
    }
}
