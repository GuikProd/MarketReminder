<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures\ORM;

use App\Domain\Builder\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Class UserFixtures.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $userBuilder = new UserBuilder();
        $userBuilderII = new UserBuilder();
        $userBuilderIII = new UserBuilder();

        $encoder = new BCryptPasswordEncoder(13);
        \Closure::fromCallable([$encoder, 'encodePassword']);

        $userBuilder->createFromRegistration(
            'hp@gmail.com',
            'HP',
            'Ie1FDLHPP',
            \Closure::fromCallable([$encoder, 'encodePassword']),
            md5(
                crypt(
                    str_rot13('HP'),
                    "hp@gmail.com"
                )
            )
        );

        $this->setReference('user', $userBuilder->getUser());

        $userBuilderII->createFromRegistration(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            \Closure::fromCallable([$encoder, 'encodePassword']),
            md5(
                crypt(
                    str_rot13('Toto'),
                    "toto@gmail.com"
                )
            )
        );

        $this->setReference('user_II', $userBuilderII->getUser());

        $userBuilderIII->createFromRegistration(
            'guik@gmail.com',
            'Guik',
            'Ie1FDLGK',
            \Closure::fromCallable([$encoder, 'encodePassword']),
            md5(
                crypt(
                    str_rot13('Guik'),
                    "guik@gmail.com"
                )
            )
        );


        $this->setReference('user_III', $userBuilderIII->getUser());

        $manager->persist($userBuilder->getUser());
        $manager->persist($userBuilderII->getUser());
        $manager->persist($userBuilderIII->getUser());

        $manager->flush();
    }
}
