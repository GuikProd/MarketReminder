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

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SecurityContext.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SecurityContext extends MinkContext implements Context
{
    /**
     * @var SessionInterface|null
     */
    private $session = null;

    /**
     * SecurityContext constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Given I log myself using the following username :username and password :password
     *
     * @param string $username  The username of the connected user.
     * @param string $password  The password of the connected user.
     */
    public function iLogMyselfUsingTheFollowingUsernameAndPassword(string $username, string $password): void
    {
        $this->session->set('_security_main', serialize(new UsernamePasswordToken($username, $password, 'main', ['ROLE_USER'])));
        $this->session->save();

        $this->getSession()->setCookie($this->session->getName(), $this->session->getId());
    }
}
