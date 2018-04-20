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

namespace App\Tests\Application\Validator;

use App\Application\Validator\ImageContent;
use App\Application\Validator\ImageContentValidator;
use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudVision\CloudVisionAnalyserHelper;
use App\Infra\GCP\CloudVision\CloudVisionDescriberHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageContentValidatorTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageContentValidatorTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyser;

    /**
     * @var CloudVisionBridgeInterface
     */
    private $cloudVisionBridge;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->cloudVisionBridge = new CloudVisionBridge(
            static::$kernel->getContainer()->getParameter('cloud.vision_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.vision_credentials')
        );

        $this->cloudVisionAnalyser = new CloudVisionAnalyserHelper($this->cloudVisionBridge);
        $this->cloudVisionDescriber = new CloudVisionDescriberHelper($this->cloudVisionBridge);

        $this->translator = static::$kernel->getContainer()->get('translator');
    }

    public function testItExtendsAndImplements()
    {
        $imageContentValidator = new ImageContentValidator(
            $this->createMock(CloudVisionAnalyserHelperInterface::class),
            $this->createMock(CloudVisionDescriberHelperInterface::class),
            $this->createMock(TranslatorInterface::class)
        );

        static::assertInstanceOf(
            ConstraintValidator::class,
            $imageContentValidator
        );

        static::assertInstanceOf(
            ImageContentValidatorInterface::class,
            $imageContentValidator
        );

        static::assertClassHasAttribute(
            'cloudVisionAnalyser',
            ImageContentValidator::class
        );

        static::assertClassHasAttribute(
            'cloudVisionDescriber',
            ImageContentValidator::class
        );
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingWhileWrongContentIsBlocked()
    {
        $imageContentValidator = new ImageContentValidator(
            $this->cloudVisionAnalyser,
            $this->cloudVisionDescriber,
            $this->translator
        );

        $toAnalyseFile = new File(
            static::$kernel->getContainer()->getParameter('kernel.project_dir').'/tests/_assets/money-world-orig.jpg'
        );

        $probe = static::$blackfire->createProbe();

        $imageContentValidator->validate(
            $toAnalyseFile,
            new ImageContent()
        );

        static::$blackfire->endProbe($probe);
    }
}
