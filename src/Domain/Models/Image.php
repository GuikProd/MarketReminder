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

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class Image.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class Image implements ImageInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $creationDate;

    /**
     * @var int
     */
    private $modificationDate;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var string
     */
    private $url;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * Image constructor.
     *
     * @param string         $alt   The replacement text defined for the image display.
     * @param string         $url   The public URL of the image.
     * @param UserInterface  $user  The user who own this image (If profile image).
     */
    public function __construct(
        string $alt,
        string $url,
        UserInterface $user = null
    ) {
        $this->id = Uuid::uuid4();
        $this->creationDate = time();
        $this->alt = $alt;
        $this->url = $url;
        $this->user = $user;
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
    public function getCreationDate(): string
    {
        return $this->creationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getModificationDate(): ? string
    {
        return $this->modificationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function setModificationDate(\DateTime $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlt():? string
    {
        return $this->alt;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl():? string
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
