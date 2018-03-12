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

namespace App\Domain\Models;

use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * Class User.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class User implements SecurityUserInterface, UserInterface, \Serializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var array
     */
    private $currentState;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     */
    private $validationDate;

    /**
     * @var bool
     */
    private $validated;

    /**
     * @var string
     */
    private $validationToken;

    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * @var ImageInterface
     */
    private $profileImage;

    /**
     * User constructor.
     *
     * @param string         $email
     * @param string         $username
     * @param string         $plainPassword
     * @param object         $passwordEncoder
     * @param array          $currentState
     * @param string         $validationToken
     * @param ImageInterface $profileImage
     */
    public function __construct(
        string $email,
        string $username,
        string $plainPassword,
        object $passwordEncoder,
        array $currentState,
        string $validationToken,
        ImageInterface $profileImage = null
    ) {
        $this->id = Uuid::uuid4();
        $this->creationDate = time();
        $this->active = false;
        $this->validated = false;
        $this->roles[] = 'ROLE_USER';
        $this->email = $email;
        $this->username = $username;
        $this->password = $passwordEncoder->encodePassword($this, $plainPassword);
        $this->currentState = $currentState;
        $this->validationToken = $validationToken;
        $this->profileImage = $profileImage;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): void
    {
        $this->active = true;
        $this->validated = true;
        $this->validationToken = '';
        $this->validationDate = time();
    }

    /**
     * {@inheritdoc}
     */
    public function askForPasswordReset(UserResetPasswordToken $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken->getResetPasswordToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): ? string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): ? string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword(): ? string
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ? string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): ? array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(): ? bool
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentState(): ? array
    {
        return $this->currentState;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationDate(): ? string
    {
        return $this->creationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationDate(): ? string
    {
        return $this->validationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationDate(\DateTime $validationDate): void
    {
        $this->validationDate = $validationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidated(): ? bool
    {
        return $this->validated;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidated(bool $validated): void
    {
        $this->validated = $validated;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationToken(string $validationToken): void
    {
        $this->validationToken = $validationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationToken(): ? string
    {
        return $this->validationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResetPasswordToken(): ? string
    {
        return $this->resetPasswordToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileImage(): ? ImageInterface
    {
        return $this->profileImage;
    }

    /**
     * {@inheritdoc}
     */
    public function setProfileImage(ImageInterface $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->active,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->active
            ) = unserialize($serialized);
    }
}
