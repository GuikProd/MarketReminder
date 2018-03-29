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
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

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
        $encoder = new BCryptPasswordEncoder(13);

        $userI = new User(
            'hp@gmail.com',
            'HP',
            'Ie1FDLHPP',
            \Closure::fromCallable([$encoder, 'encodePassword']),
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
            'Ie1FDLTOTO',
            \Closure::fromCallable([$encoder, 'encodePassword']),
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
            'Ie1FDLGK',
            \Closure::fromCallable([$encoder, 'encodePassword']),
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
