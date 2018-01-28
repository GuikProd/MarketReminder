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

namespace App\Subscriber\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Subscriber\Interfaces\RegisterTypeSubscriberInterface;

/**
 * Class RegisterTypeSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeSubscriber implements RegisterTypeSubscriberInterface
{
    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * RegisterTypeSubscriber constructor.
     *
     * @param ImageUploaderHelperInterface $imageUploaderHelper
     */
    public function __construct(ImageUploaderHelperInterface $imageUploaderHelper)
    {
        $this->imageUploaderHelper = $imageUploaderHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $events): void
    {
        if ($events->getData() == null) {
            return;
        }

        $this->imageUploaderHelper
             ->store($events->getData())
             ->upload();
    }
}
