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

use App\UI\Action\Security\LoginAction;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\LoginResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class LoginActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class LoginActionUnitTest extends TestCase
{
    /**
     * @var FormFactoryInterface|null
     */
    private $formFactory = null;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
        $this->request = Request::create('/fr/login', 'GET');
        $this->twig = $this->createMock(Environment::class);
    }

    public function testInvokeReturn()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $this->formFactory->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturnSelf();

        $loginAction = new LoginAction($this->formFactory);
        $loginResponder = new LoginResponder($this->twig, $this->presenter);

        static::assertInstanceOf(
            Response::class,
            $loginAction($this->request, $loginResponder)
        );
    }
}
