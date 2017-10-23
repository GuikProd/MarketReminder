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

use App\Models\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProductsFixtures
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProductsFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $products = new Products();

        $products->setLabel('Salade');
        $products->setEntryDate(new \DateTime('2017-03-21'));
        $products->setModificationDate(new \DateTime('2017-03-21'));
        $products->setOutDate(new \DateTime('2017-03-21'));
        $products->setQuantity(120);
        $products->setStatus('Added');
        $products->setLimiteConsumptionDate(new \DateTime('2017-03-30'));
        $products->setLimiteUsageDate(new \DateTime('2017-03-30'));
        $products->setStock($this->getReference('stock'));

        $products_II = new Products();

        $products_II->setLabel('Petit pois');
        $products_II->setEntryDate(new \DateTime('2017-03-21'));
        $products_II->setModificationDate(new \DateTime('2017-03-21'));
        $products_II->setOutDate(new \DateTime('2017-03-21'));
        $products_II->setQuantity(120);
        $products_II->setStatus('Added');
        $products_II->setLimiteConsumptionDate(new \DateTime('2020-03-21'));
        $products_II->setLimiteUsageDate(new \DateTime('2020-03-21'));
        $products_II->setStock($this->getReference('stock'));

        $products_III = new Products();

        $products_III->setLabel('Boeuf');
        $products_III->setEntryDate(new \DateTime('2017-03-21'));
        $products_III->setModificationDate(new \DateTime('2017-03-21'));
        $products_III->setOutDate(new \DateTime('2017-03-21'));
        $products_III->setQuantity(120);
        $products_III->setStatus('Added');
        $products_III->setLimiteConsumptionDate(new \DateTime('2017-04-21'));
        $products_III->setLimiteUsageDate(new \DateTime('2017-04-21'));
        $products_III->setStock($this->getReference('stock_II'));

        $products_IV = new Products();

        $products_IV->setLabel('Pâtes');
        $products_IV->setEntryDate(new \DateTime('2017-03-21'));
        $products_IV->setModificationDate(new \DateTime('2017-03-21'));
        $products_IV->setOutDate(new \DateTime('2017-03-21'));
        $products_IV->setQuantity(120);
        $products_IV->setStatus('Added');
        $products_IV->setLimiteConsumptionDate(new \DateTime('2025-03-21'));
        $products_IV->setLimiteUsageDate(new \DateTime('2025-03-21'));
        $products_IV->setStock($this->getReference('stock_II'));

        $manager->persist($products);
        $manager->persist($products_II);
        $manager->persist($products_III);
        $manager->persist($products_IV);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            StockFixtures::class
        ];
    }
}
