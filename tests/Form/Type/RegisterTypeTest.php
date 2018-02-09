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

namespace tests\Form\Type;

use App\Builder\UserBuilder;
use App\Form\Type\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use App\Subscriber\Form\ProfileImageSubscriber;
use App\Builder\Interfaces\UserBuilderInterface;
use App\Builder\Interfaces\ImageBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Form\RegisterCredentialsSubscriber;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Subscriber\Interfaces\RegisterCredentialsSubscriberInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;

/**
 * Class RegisterTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeTest extends TypeTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translatorInterface;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilderInterface;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilderInterface;

    /**
     * @var ProfileImageSubscriberInterface
     */
    private $profileImageSubscriber;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelperInterface;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelperInterface;

    /**
     * @var RegisterCredentialsSubscriberInterface
     */
    private $registerCredentialsSubscriber;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoterHelperInterface;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyserHelperInterface;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriberHelperInterface;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->translatorInterface = $this->createMock(TranslatorInterface::class);

        $this->imageBuilderInterface = $this->createMock(ImageBuilderInterface::class);

        $this->entityManagerInterface = $this->createMock(EntityManagerInterface::class);

        $this->imageUploaderHelperInterface = $this->createMock(ImageUploaderHelperInterface::class);

        $this->imageRetrieverHelperInterface = $this->createMock(ImageRetrieverHelperInterface::class);

        $this->cloudVisionVoterHelperInterface = $this->createMock(CloudVisionVoterHelperInterface::class);

        $this->cloudVisionAnalyserHelperInterface = $this->createMock(CloudVisionAnalyserHelperInterface::class);

        $this->cloudVisionDescriberHelperInterface = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $this->profileImageSubscriber = new ProfileImageSubscriber(
                                            $this->translatorInterface,
                                            $this->imageBuilderInterface,
                                            $this->cloudVisionVoterHelperInterface,
                                            $this->imageUploaderHelperInterface,
                                            $this->cloudVisionAnalyserHelperInterface,
                                            $this->imageRetrieverHelperInterface,
                                            $this->cloudVisionDescriberHelperInterface
                                        );

        $this->registerCredentialsSubscriber = new RegisterCredentialsSubscriber(
                                                   $this->translatorInterface,
                                                   $this->entityManagerInterface
                                               );

        $this->userBuilderInterface = new UserBuilder();

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        $type = new RegisterType($this->profileImageSubscriber, $this->registerCredentialsSubscriber);

        return [
            new PreloadedExtension(
                [$type],
                []
            ),
        ];
    }

    public function testDataSubmission()
    {
        $userBuilder = $this->userBuilderInterface
                            ->createUser()
                            ->withUsername('Tototo')
                            ->withEmail('toto@gmail.com')
                            ->withPlainPassword('Ie1FDLTOTO');

        $registerType = $this->factory->create(RegisterType::class, $userBuilder->getUser());

        static::assertTrue(
            $registerType->isSynchronized()
        );

        static::assertEquals(
            $userBuilder->getUser(),
            $registerType->getData()
        );
    }
}
