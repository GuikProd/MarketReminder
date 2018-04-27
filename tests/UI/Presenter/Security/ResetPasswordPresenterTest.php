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

/**
 * Class ResetPasswordPresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordPresenterTest extends TestCase
{
    use TestCaseTrait;

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
            'notification' => [
                'content' => 'user.notification.reset_password_success'
            ],
            'page' => [
                'title' => 'reset_password.title'
            ]
        ]);

        static::$blackfire->endProbe($probe);
    }

    public function testAllDefinitionsAreFound()
    {
        $resetPasswordPresenter = new ResetPasswordPresenter();

        $resetPasswordPresenter->prepareOptions([
            'notification' => [
                'content' => 'user.notification.reset_password_success'
            ],
            'page' => [
                'title' => 'reset_password.title'
            ]
        ]);

        static::assertArrayHasKey('content', $resetPasswordPresenter->getNotificationMessage());
        static::assertArrayHasKey('title', $resetPasswordPresenter->getPage());
    }
}
