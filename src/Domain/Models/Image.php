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
     * @var string
     */
    private $alt;

    /**
     * @var string
     */
    private $publicUrl;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $creationDate;

    /**
     * @var int
     */
    private $modificationDate;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $alt,
        string $filename,
        string $publicUrl
    ) {
        $this->id = Uuid::uuid4();
        $this->creationDate = time();
        $this->alt = $alt;
        $this->filename = $filename;
        $this->publicUrl = $publicUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationDate(): \DateTime
    {
        return \DateTime::createFromFormat('U', $this->creationDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getModificationDate():? \DateTime
    {
        return \DateTime::createFromFormat('U', $this->modificationDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicUrl(): string
    {
        return $this->publicUrl;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}
