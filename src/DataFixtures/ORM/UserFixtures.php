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

namespace App\DataFixtures\ORM;

use App\Domain\Models\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $userI = new User(
            'hp@gmail.com',
            'HP',
            password_hash('Ie1FDLHPP', PASSWORD_BCRYPT, ['cost' => 13]),
            crypt(
                str_rot13(
                    str_shuffle(
                        'hp@gmail.com'
                    )
                ),
                'Ie1FDLHPP'
            )
        );

        $this->setReference('user', $userI);

        $userII = new User(
            'toto@gmail.com',
            'Toto',

            password_hash('Ie1FDLTOTO', PASSWORD_BCRYPT, ['cost' => 13]),
            crypt(
                str_rot13(
                    str_shuffle(
                        'toto@gmail.com'
                    )
                ),
                'Ie1FDLTOTO'
            )

        );

        $this->setReference('user_II', $userII);

        $userIII = new User(
            'guik@gmail.com',
            'Guik',
            password_hash('Ie1FDLGK', PASSWORD_BCRYPT, ['cost' => 13]),
            crypt(
                str_rot13(
                    str_shuffle(
                        'guik@gmail.com'
                    )
                ),
                'Ie1FDLGuik'
            )
        );

        $this->setReference('user_III', $userIII);

        $manager->persist($userI);
        $manager->persist($userII);
        $manager->persist($userIII);

        $manager->flush();
    }
}
