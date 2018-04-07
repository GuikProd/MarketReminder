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

namespace App\UI\Form\Type\Interfaces;

use App\Application\Symfony\Subscriber\Interfaces\ImageUploadSubscriberInterface;

/**
 * Interface ImageUploadTypeInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageUploadTypeInterface
{
    /**
     * ImageUploadTypeInterface constructor.
     * 
     * @param ImageUploadSubscriberInterface $imageUploadSubscriber
     */
    public function __construct(ImageUploadSubscriberInterface $imageUploadSubscriber);
}