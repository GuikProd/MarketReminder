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

namespace App\Interactor;

use App\Models\User;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class UserInteractor.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserInteractor extends User implements AdvancedUserInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(): void
    {
        $this->validated = true;
        $this->validationToken = '';
        $this->validationDate = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
