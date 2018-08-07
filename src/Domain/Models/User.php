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

namespace App\Domain\Models;

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * Class User.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class User implements SecurityUserInterface, UserInterface, \Serializable
{
    /**
     * @var string
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
    private $password;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var array
     */
    private $currentState = [];

    /**
     * @var \DateTimeInterface
     */
    private $creationDate;

    /**
     * @var \DateTimeInterface
     */
    private $validationDate;

    /**
     * @var bool
     */
    private $validated = false;

    /**
     * @var string
     */
    private $validationToken;

    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * @var int
     */
    private $askResetPasswordDate;

    /**
     * @var int
     */
    private $resetPasswordDate;

    /**
     * @var ImageInterface|null
     */
    private $profileImage = null;

    /**
     * @var array
     */
    private $stocks = [];

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __construct(
        string $email,
        string $username,
        string $password,
        ImageInterface $profileImage = null
    ) {
        $this->active = false;
        $this->currentState = ['toValidate'];
        $this->id = Uuid::uuid4();
        $this->creationDate = new \DateTimeImmutable();
        $this->email = $email;
        $this->password = $password;
        $this->profileImage = $profileImage;
        $this->username = $username;
        $this->validated = false;
        $this->validationToken = md5(str_rot13($username));
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception @see \DatetimeImmutable
     */
    public function validate(): void
    {
        $this->active = true;
        $this->validated = true;
        $this->validationToken = null;
        $this->validationDate = new \DateTimeImmutable();
        $this->roles[] = 'ROLE_USER';
    }

    /**
     * {@inheritdoc}
     */
    public function askForPasswordReset(UserResetPasswordToken $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken->getResetPasswordToken();
        $this->askResetPasswordDate = time();
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(string $newPassword): void
    {
        $this->password = $newPassword;
        $this->resetPasswordDate = new \DateTime();
        $this->resetPasswordToken = null;
    }

    /**
     * {@inheritdoc}
     */
    public function changeProfileImage(ImageInterface $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    /**
     * {@inheritdoc}
     */
    public function addStock(StockInterface $stock): void
    {
        $this->stocks[] = $stock;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(): bool
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
    public function getCreationDate(): string
    {
        return $this->creationDate->format('d-M-Y H:i:s');
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
    public function getValidationToken(): ? string
    {
        return $this->validationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationDate(): ?string
    {
        return $this->validationDate->format('d-M-Y H:i:s');
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
    public function getAskResetPasswordDate(): ? int
    {
        return $this->askResetPasswordDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getResetPasswordDate():? int
    {
        return $this->resetPasswordDate;
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
    public function getStocks(): array
    {
        return $this->stocks;
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
