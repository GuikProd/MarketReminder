<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures\ORM;

use App\Models\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ImageFixtures
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $image = new Image();

        $image->setCreationDate(new \DateTime('2017-03-21'));
        $image->setAlt('New Image');
        $image->setUrl('http://localhost/public/images/new_image.png');
        $image->setUser($this->getReference('user'));

        $image_II = new Image();

        $image_II->setCreationDate(new \DateTime('2017-03-21'));
        $image_II->setAlt('New Image');
        $image_II->setUrl('http://localhost/public/images/new_image_II.png');
        $image_II->setUser($this->getReference('user_II'));

        $image_III = new Image();

        $image_III->setCreationDate(new \DateTime('2017-03-21'));
        $image_III->setAlt('New Image');
        $image_III->setUrl('http://localhost/public/images/new_image_III.png');
        $image_III->setUser($this->getReference('user_III'));

        $manager->persist($image);
        $manager->persist($image_II);
        $manager->persist($image_III);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
