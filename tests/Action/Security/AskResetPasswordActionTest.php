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

namespace tests\Action\Security;

use PHPUnit\Framework\TestCase;
use App\Action\Security\AskResetPasswordAction;

/**
 * Class AskResetPasswordActionTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordActionTest extends TestCase
{
    public function testInvokeReturn()
    {
        $askResetPasswordAction = new AskResetPasswordAction();
    }
}
