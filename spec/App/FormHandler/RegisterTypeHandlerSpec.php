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

namespace spec\App\FormHandler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Workflow\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Builder\Interfaces\UserBuilderInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Workflow\Workflow;

/**
 * Class RegisterTypeHandlerSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|Workflow                     $registry
     * @param EntityManagerInterface|\PhpSpec\Wrapper\Collaborator       $entityManager
     * @param \PhpSpec\Wrapper\Collaborator|UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function it_implements(
        Workflow $registry,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->beConstructedWith($registry, $entityManager, $userPasswordEncoder);
        $this->shouldImplement(RegisterTypeHandlerInterface::class);
    }

    /**
     * @param Workflow                     $registry
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param FormInterface                $form
     * @param UserBuilderInterface         $userBuilder
     */
    public function should_return_false(
        Workflow $registry,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder,
        FormInterface $form,
        UserBuilderInterface $userBuilder
    ) {
        $this->beConstructedWith($registry, $entityManager, $userPasswordEncoder);
        $this->handle($form, $userBuilder)->shouldReturn(false);
    }
}
