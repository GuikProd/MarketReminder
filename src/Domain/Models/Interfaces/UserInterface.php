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

namespace App\Domain\Models\Interfaces;

use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;

/**
 * Interface UserInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserInterface
{
    /**
     * @param UserResetPasswordToken $userPasswordReset
     */
    public function askForPasswordReset(UserResetPasswordToken $userPasswordReset): void;

    /**
     * @return int|null
     */
    public function getId(): ? int;

    /**
     * @return null|string
     */
    public function getUsername(): ? string;

    /**
     * @return null|string
     */
    public function getEmail(): ? string;

    /**
     * @return null|string
     */
    public function getPlainPassword(): ? string;

    /**
     * @return null|string
     */
    public function getPassword(): ? string;

    /**
     * @return array
     */
    public function getRoles(): ? array;

    /**
     * @return bool
     */
    public function getActive(): ? bool;

    /**
     * @return string
     */
    public function getCreationDate(): ? string;

    /**
     * @param \DateTime $validationDate
     */
    public function setValidationDate(\DateTime $validationDate): void;

    /**
     * @return null|string
     */
    public function getValidationDate(): ? string;

    /**
     * @return array
     */
    public function getCurrentState(): ? array;

    /**
     * @return bool
     */
    public function getValidated(): ? bool;

    /**
     * @param string $validationToken
     */
    public function setValidationToken(string $validationToken): void;

    /**
     * @return null|string
     */
    public function getValidationToken(): ? string;

    /**
     * @return null|string
     */
    public function getResetPasswordToken():? string;

    /**
     * @param ImageInterface $profileImage
     */
    public function setProfileImage(ImageInterface $profileImage): void;

    /**
     * @return null|ImageInterface
     */
    public function getProfileImage():? ImageInterface;

    /**
     * Allow to validate the user once he's registered.
     */
    public function validate(): void;
}
