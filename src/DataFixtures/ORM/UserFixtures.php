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

use App\Builder\UserBuilder;
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
        $passwordEncoder = $this->container->get('security.password_encoder');

        $userBuilder = new UserBuilder();

        $userBuilder
            ->createUser()
            ->withUsername('HP')
            ->withEmail('hp@gmail.com')
            ->withPlainPassword('Ie1FDLHPP')
            ->withRole('ROLE_WIZARD')
            ->withCreationDate(new \DateTime('2017-03-21'))
            ->withValidated(true)
            ->withActive(true)
            ->withValidationToken(
                crypt(
                    str_rot13(
                        str_shuffle(
                            $userBuilder->getUser()->getEmail()
                        )
                    ),
                    $userBuilder->getUser()->getUsername()
                )
            )
            ->withCurrentState(['active']);

        $this->setReference('user', $userBuilder->getUser());

        $password = $passwordEncoder->encodePassword(
            $userBuilder->getUser(),
            $userBuilder->getUser()->getPlainPassword()
        );
        $userBuilder->withPassword($password);

        $userBuilder_II = new UserBuilder();

        $userBuilder_II
            ->createUser()
            ->withUsername('Toto')
            ->withEmail('toto@gmail.com')
            ->withPlainPassword('Ie1FDLTOTO')
            ->withRole('ROLE_USER')
            ->withCreationDate(new \DateTime('2017-03-21'))
            ->withValidated(true)
            ->withActive(true)
            ->withValidationToken(
                crypt(
                    str_rot13(
                        str_shuffle(
                            $userBuilder_II->getUser()->getEmail()
                        )
                    ),
                    $userBuilder_II->getUser()->getUsername()
                )
            )
            ->withCurrentState(['active']);

        $this->setReference('user_II', $userBuilder_II->getUser());

        $password = $passwordEncoder->encodePassword(
            $userBuilder_II->getUser(),
            $userBuilder_II->getUser()->getPlainPassword()
        );
        $userBuilder_II->withPassword($password);

        $manager->persist($userBuilder->getUser());
        $manager->persist($userBuilder_II->getUser());

        $manager->flush();
    }
}
