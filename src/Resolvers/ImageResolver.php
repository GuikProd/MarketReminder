<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolvers;

use App\Models\Image;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * Class ImageResolver.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageResolver implements ResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ImageResolver constructor.
     *
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
    }

    /**
     * @param Argument $argument
     *
     * @return Image[]|array
     */
    public function getImage(Argument $argument)
    {
        if ($argument->offsetExists('id')) {
            return [
                $this->entityManager->getRepository(Image::class)
                                    ->findOneBy([
                                      'id' => $argument->offsetGet('id'),
                                    ]),
            ];
        }

        return $this->entityManager->getRepository(Image::class)->findAll();
    }
}
