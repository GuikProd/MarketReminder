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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWarmerTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->cloudTranslationBridge = new CloudTranslationBridge(
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
        );
    }

    public function testItImplements()
    {
        $cloudTranslationWarmer = new CloudTranslationWarmer($this->cloudTranslationBridge);

        static::assertInstanceOf(
            CloudTranslationWarmerInterface::class,
            $cloudTranslationWarmer
        );
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testBlackfireProfilingItTranslateASingleElement()
    {
        $cloudTranslationWarmer = new CloudTranslationWarmer($this->cloudTranslationBridge);

        $probe = static::$blackfire->createProbe();

        $cloudTranslationWarmer->warmTranslation('Bien le bonjour', 'en');

        static::$blackfire->endProbe($probe);
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testBlackfireProfilingItTranslateAnArrayOfElements()
    {
        $cloudTranslationWarmer = new CloudTranslationWarmer($this->cloudTranslationBridge);

        $probe = static::$blackfire->createProbe();

        $cloudTranslationWarmer->warmArrayTranslation([
            'Bien le bonjour',
            'Petit test'
        ], 'en');

        static::$blackfire->endProbe($probe);
    }

    public function testItTranslateASingleElement()
    {
        $cloudTranslationWarmer = new CloudTranslationWarmer($this->cloudTranslationBridge);

        $translatedText = $cloudTranslationWarmer->warmTranslation('Petit test', 'en');

        static::assertNotNull($translatedText);
    }
}
