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

namespace App\Domain\Models\Interfaces;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface ImageInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ImageInterface
{
    /**
     * ImageInterface constructor.
     *
     * @param string  $alt        The alt of the image (by default, the filename).
     * @param string  $filename   The name of the Image once stored.
     * @param string  $publicUrl  The public URL (using GCP) of the file.
     *
     * @internal The constructor should define an UUID along with a creationDate using time().
     */
    public function __construct(
        string $alt,
        string $filename,
        string $publicUrl
    );

    /**
     * The UUID generated during object construction.
     *
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime;

    /**
     * @return null|\DateTime
     */
    public function getModificationDate():? \DateTime;

    /**
     * @return string
     */
    public function getAlt(): string;

    /**
     * @return string
     */
    public function getPublicUrl(): string;

    /**
     * @return string
     */
    public function getFilename(): string;
}
