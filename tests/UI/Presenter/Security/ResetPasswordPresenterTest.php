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

namespace App\Tests\UI\Presenter\Security;

use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use App\UI\Presenter\Security\ResetPasswordPresenter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class ResetPasswordPresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordPresenterTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var FormInterface
     */
    private $formInterface;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->formInterface = $this->createMock(FormInterface::class);

        $this->formInterface->method('createView')
                            ->willReturn($this->createMock(FormView::class));
    }

    public function testItImplements()
    {
        $resetPasswordPresenter = new ResetPasswordPresenter();

        static::assertInstanceOf(AbstractPresenter::class, $resetPasswordPresenter);
        static::assertInstanceOf(ResetPasswordPresenterInterface::class, $resetPasswordPresenter);
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testBlackfireProfilingAlongWithAllDefinitions()
    {
        $resetPasswordPresenter = new ResetPasswordPresenter();

        $probe = static::$blackfire->createProbe();

        $resetPasswordPresenter->prepareOptions([
            'form' => $this->formInterface,
            'notification' => [
                'content' => 'user.notification.reset_password_success'
            ],
            'page' => [
                'title' => 'reset_password.title',
                'button' => [
                    'content' => ''
                ]
            ]
        ]);

        static::$blackfire->endProbe($probe);
    }

    public function testAllDefinitionsAreFound()
    {
        $resetPasswordPresenter = new ResetPasswordPresenter();

        $resetPasswordPresenter->prepareOptions([
            'form' => $this->formInterface,
            'notification' => [
                'content' => 'user.notification.reset_password_success'
            ],
            'page' => [
                'title' => 'reset_password.title',
                'button' => [
                    'content' => ''
                ]
            ]
        ]);

        static::assertInstanceOf(FormView::class, $resetPasswordPresenter->getForm());
        static::assertArrayHasKey('content', $resetPasswordPresenter->getNotificationMessage());
        static::assertArrayHasKey('title', $resetPasswordPresenter->getPage());
        static::assertArrayHasKey('button', $resetPasswordPresenter->getPage());
    }
}
