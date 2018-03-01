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

namespace App\Builder\Interfaces;

use App\Models\Interfaces\UserInterface;
use App\Models\Interfaces\ImageInterface;

/**
 * Interface UserBuilderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserBuilderInterface
{
    /**
     * @return UserBuilderInterface
     */
    public function createUser(): self;

    /**
     * @param string $username
     *
     * @return UserBuilderInterface
     */
    public function withUsername(string $username): self;

    /**
     * @param string $email
     *
     * @return UserBuilderInterface
     */
    public function withEmail(string $email): self;

    /**
     * @param string $plainPassword
     *
     * @return UserBuilderInterface
     */
    public function withPlainPassword(string $plainPassword): self;

    /**
     * @param string $password
     *
     * @return UserBuilderInterface
     */
    public function withPassword(string $password): self;

    /**
     * @param string $role
     *
     * @return UserBuilderInterface
     */
    public function withRole(string $role): self;

    /**
     * @param \DateTime $creationDate
     *
     * @return UserBuilderInterface
     */
    public function withCreationDate(\DateTime $creationDate): self;

    /**
     * @param \DateTime $validationDate
     *
     * @return UserBuilderInterface
     */
    public function withValidationDate(\DateTime $validationDate): self;

    /**
     * @param bool $validated
     *
     * @return UserBuilderInterface
     */
    public function withValidated(bool $validated): self;

    /**
     * @param string $validationToken
     *
     * @return UserBuilderInterface
     */
    public function withValidationToken(string $validationToken): self;

    /**
     * @param string $resetPasswordToken
     *
     * @return UserBuilderInterface
     */
    public function withResetPasswordToken(string $resetPasswordToken): self;

    /**
     * @param bool $active
     *
     * @return UserBuilderInterface
     */
    public function withActive(bool $active): self;

    /**
     * @param array $currentState
     *
     * @return UserBuilderInterface
     */
    public function withCurrentState(array $currentState): self;

    /**
     * @param ImageInterface $profileImage
     *
     * @return UserBuilderInterface
     */
    public function withProfileImage(ImageInterface $profileImage): self;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}
