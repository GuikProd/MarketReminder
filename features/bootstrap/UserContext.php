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

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use Behat\Behat\Context\Context;

/**
 * Class UserContext.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserContext implements Context
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @Given I need to create a user account with the following username :username and email :email and password :password
     *
     * @throws Exception
     */
    public function iNeedToCreateAUserAccountWithTheFollowingUsernameAndEmailAndPassword(
        string $username,
        string $email,
        string $password
    ): void {

        $this->user = new User(
            $email,
            $username,
            $password
        );
    }

    /**
     * @Then I should not be active
     */
    public function iShouldNotBeActive()
    {
        if ($this->user->getActive()) {
            throw new \LogicException(sprintf('The user should not be active without validating his token.'));
        }
    }

    /**
     * @Then I should have a validation token
     */
    public function iShouldHaveAValidationToken()
    {
        if (!$this->user->getValidationToken()) {
            throw new \LogicException(sprintf('The user should have a validation token.'));
        }
    }

    /**
     * @Given I validate my account.
     */
    public function iValidateMyAccount()
    {
        $this->user->validate();
    }

    /**
     * @Then I should be active and have a role user.
     */
    public function iShouldBeActiveAndHaveARoleUser()
    {
        if (!$this->user->getActive() && !\in_array('ROLE_USER', $this->user->getRoles())) {
            throw new \LogicException(sprintf('This user should have validate his account !'));
        }
    }
}
