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

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Security\AskResetPasswordPresenter;
use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;

/**
 * Class AskResetPasswordPresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordPresenterTest extends TestCase
{
    use TestCaseTrait;

    public function testItImplements()
    {
        $askResetPasswordPresenter = new AskResetPasswordPresenter();

        static::assertInstanceOf(PresenterInterface::class, $askResetPasswordPresenter);
        static::assertInstanceOf(AskResetPasswordPresenterInterface::class, $askResetPasswordPresenter);
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingWithOptionsResolving()
    {
        $askResetPasswordPresenter = new AskResetPasswordPresenter();

        $probe = static::$blackfire->createProbe();

        $askResetPasswordPresenter->prepareOptions([
            'form' => $this->createMock(FormView::class),
            'card_header' => 'Reset password',
            'card_button' => 'Reset',
            'page_title' => 'Reset Password'
        ]);

        static::$blackfire->endProbe($probe);

        static::assertArrayHasKey('form', $askResetPasswordPresenter->getViewOptions());
    }
}
