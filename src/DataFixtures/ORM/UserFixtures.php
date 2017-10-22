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

use App\Models\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures
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

        $user = new User();

        $user->setFirstname('Harry');
        $user->setLastname('Potter');
        $user->setUsername('HP');
        $user->setEmail('hp@gmail.com');
        $user->setPlainPassword('Ie1FDLHPP');
        $user->setPassword('Ie1FDLHPP');
        $user->setRole('ROLE_WIZARD');
        $user->setCreationDate(new \DateTime('2017-03-21'));
        $user->setValidationDate(new \DateTime('2017-03-21'));
        $user->setValidated(true);
        $user->setActive(true);
        $user->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4s49');

        $user_II = new User();

        $user_II->setFirstname('Tom');
        $user_II->setLastname('Potter');
        $user_II->setUsername('TP');
        $user_II->setEmail('tp@gmail.com');
        $user_II->setPlainPassword('Ie1FDLHP');
        $user_II->setPassword('Ie1FDLHPP');
        $user_II->setRole('ROLE_WIZARD');
        $user_II->setCreationDate(new \DateTime('2017-03-21'));
        $user_II->setValidationDate(new \DateTime('2017-03-21'));
        $user_II->setValidated(true);
        $user_II->setActive(true);
        $user_II->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as44a5d');

        $user_III = new User();

        $user_III->setFirstname('Lilly');
        $user_III->setLastname('Potter');
        $user_III->setUsername('LP');
        $user_III->setEmail('lp@gmail.com');
        $user_III->setPlainPassword('Ie1FDLL');
        $user_III->setPassword('Ie1FDLHPP');
        $user_III->setRole('ROLE_WIZARD');
        $user_III->setCreationDate(new \DateTime('2017-03-21'));
        $user_III->setValidationDate(new \DateTime('2017-03-21'));
        $user_III->setValidated(true);
        $user_III->setActive(true);
        $user_III->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4a1d8');

        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $password_II = $passwordEncoder->encodePassword($user_II, $user_II->getPlainPassword());
        $user_II->setPassword($password_II);

        $password_III = $passwordEncoder->encodePassword($user_III, $user_III->getPlainPassword());
        $user_III->setPassword($password_III);

        $manager->persist($user);
        $manager->persist($user_II);
        $manager->persist($user_III);

        $manager->flush();
    }
}
