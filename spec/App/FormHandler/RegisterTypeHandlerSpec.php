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
use App\Builder\Interfaces\ImageBuilderInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterTypeHandlerSpec.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|Registry $registry
     * @param ImageBuilderInterface|\PhpSpec\Wrapper\Collaborator $imageBuilder
     * @param EntityManagerInterface|\PhpSpec\Wrapper\Collaborator $entityManager
     * @param ImageUploaderHelperInterface|\PhpSpec\Wrapper\Collaborator $imageUploaderHelper
     * @param \PhpSpec\Wrapper\Collaborator|UserPasswordEncoderInterface $userPasswordEncoder
     * @param ImageRetrieverHelperInterface|\PhpSpec\Wrapper\Collaborator $imageRetrieverHelper
     */
    public function it_implements(
        Registry $registry,
        ImageBuilderInterface $imageBuilder,
        EntityManagerInterface $entityManager,
        ImageUploaderHelperInterface $imageUploaderHelper,
        UserPasswordEncoderInterface $userPasswordEncoder,
        ImageRetrieverHelperInterface $imageRetrieverHelper
    ) {
        $this->beConstructedWith($registry, $imageBuilder, $entityManager, $imageUploaderHelper, $userPasswordEncoder, $imageRetrieverHelper);
        $this->shouldImplement(RegisterTypeHandlerInterface::class);
    }
}
