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

use App\Models\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class StockFixtures
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class StockFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        $stock = new Stock();

        $stock->setCreationDate(new \DateTime('2017-03-21'));
        $stock->setModificationDate(new \DateTime('2017-03-21'));
        $stock->setStatus('In use');

        $stock_II = new Stock();

        $stock_II->setCreationDate(new \DateTime('2017-03-23'));
        $stock_II->setModificationDate(new \DateTime('2017-03-23'));
        $stock_II->setStatus('Paused');

        $stock_III = new Stock();

        $stock_III->setCreationDate(new \DateTime('2017-03-30'));
        $stock_III->setModificationDate(new \DateTime('2017-03-30'));
        $stock_III->setStatus('Stopped');

        $manager->persist($stock);
        $manager->persist($stock_II);
        $manager->persist($stock_III);

        $manager->flush();
    }
}
