<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Repository;

use App\Models\Image;
use Doctrine\ORM\EntityManager;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ImageRepositoryTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testRepositoryExist()
    {
        static::assertInstanceOf(
            ImageRepository::class,
            $this->entityManager->getRepository(Image::class)
        );
    }
}
