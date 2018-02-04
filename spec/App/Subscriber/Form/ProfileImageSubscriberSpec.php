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
use App\Builder\Interfaces\ImageBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;

/**
 * Class ProfileImageSubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|TranslatorInterface                 $translator
     * @param ImageBuilderInterface|\PhpSpec\Wrapper\Collaborator               $imageBuilder
     * @param CloudVisionVoterHelperInterface|\PhpSpec\Wrapper\Collaborator     $cloudVisionVoterHelper
     * @param ImageUploaderHelperInterface|\PhpSpec\Wrapper\Collaborator        $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface|\PhpSpec\Wrapper\Collaborator  $cloudVisionAnalyserHelper
     * @param ImageRetrieverHelperInterface|\PhpSpec\Wrapper\Collaborator       $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface|\PhpSpec\Wrapper\Collaborator $cloudVisionDescriberHelper
     */
    public function it_implements(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
    ) {
        $this->beConstructedWith(
            $translator,
            $imageBuilder,
            $cloudVisionVoterHelper,
            $imageUploaderHelper,
            $cloudVisionAnalyserHelper,
            $imageRetrieverHelper,
            $cloudVisionDescriberHelper
        );
        $this->shouldImplement(ProfileImageSubscriberInterface::class);
    }

    /**
     * @param TranslatorInterface                 $translator
     * @param ImageBuilderInterface               $imageBuilder
     * @param CloudVisionVoterHelperInterface     $cloudVisionVoterHelper
     * @param ImageUploaderHelperInterface        $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface  $cloudVisionAnalyserHelper
     * @param ImageRetrieverHelperInterface       $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
     */
    public function should_return_events(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
    ) {
        $this->beConstructedWith(
            $translator,
            $imageBuilder,
            $cloudVisionVoterHelper,
            $imageUploaderHelper,
            $cloudVisionAnalyserHelper,
            $imageRetrieverHelper,
            $cloudVisionDescriberHelper
        );
        $this::getSubscribedEvents()->shouldBeArray();
        $this::getSubscribedEvents()->shouldContain('onSubmit');
    }

    /**
     * @param TranslatorInterface                 $translator
     * @param ImageBuilderInterface               $imageBuilder
     * @param CloudVisionVoterHelperInterface     $cloudVisionVoterHelper
     * @param ImageUploaderHelperInterface        $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface  $cloudVisionAnalyserHelper
     * @param ImageRetrieverHelperInterface       $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
     * @param FormEvent                           $event
     */
    public function should_return_void(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        FormEvent $event
    ) {
        $this->beConstructedWith(
            $translator,
            $imageBuilder,
            $cloudVisionVoterHelper,
            $imageUploaderHelper,
            $cloudVisionAnalyserHelper,
            $imageRetrieverHelper,
            $cloudVisionDescriberHelper
        );
        $this->onSubmit($event)->shouldReturn(null);
    }
}
