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

namespace spec\App\Subscriber\Form;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Translation\TranslatorInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;

/**
 * Class ProfileImageSubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|TranslatorInterface                $translator
     * @param ImageUploaderHelperInterface|\PhpSpec\Wrapper\Collaborator       $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface|\PhpSpec\Wrapper\Collaborator $cloudVisionAnalyserHelper
     */
    public function it_implements(
        TranslatorInterface $translator,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper
    ) {
        $this->beConstructedWith($translator, $imageUploaderHelper, $cloudVisionAnalyserHelper);
        $this->shouldImplement(ProfileImageSubscriberInterface::class);
    }

    /**
     * @param TranslatorInterface                $translator
     * @param ImageUploaderHelperInterface       $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper
     */
    public function should_return_events(
        TranslatorInterface $translator,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper
    ) {
        $this->beConstructedWith($translator, $imageUploaderHelper, $cloudVisionAnalyserHelper);
        $this::getSubscribedEvents()->shouldBeArray();
        $this::getSubscribedEvents()->shouldContain('onSubmit');
    }

    /**
     * @param TranslatorInterface                $translator
     * @param ImageUploaderHelperInterface       $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper
     * @param FormEvent                          $event
     */
    public function should_return_void(
        TranslatorInterface $translator,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        FormEvent $event
    ) {
        $this->beConstructedWith($translator, $imageUploaderHelper, $cloudVisionAnalyserHelper);
        $this->onSubmit($event)->shouldReturn(null);
    }

    /**
     * @param TranslatorInterface                $translator
     * @param ImageUploaderHelperInterface       $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper
     * @param FormEvent                          $event
     */
    public function should_analyse_file(
        TranslatorInterface $translator,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        FormEvent $event
    ) {
        $this->beConstructedWith($translator, $imageUploaderHelper, $cloudVisionAnalyserHelper);
        $this->uploadAndAnalyseImage($event)->shouldReturn(null);
    }
}
