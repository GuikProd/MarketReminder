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

namespace spec\App\Event\Image;

use PhpSpec\ObjectBehavior;
use App\Models\Interfaces\ImageInterface;
use App\Event\Interfaces\ImageEventInterface;

/**
 * Class ImageAddedEventSpec
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageAddedEventSpec extends ObjectBehavior
{
    /**
     * @param ImageInterface|\PhpSpec\Wrapper\Collaborator $image
     */
    public function it_implement(ImageInterface $image)
    {
        $this->beConstructedWith($image);
        $this->shouldImplement(ImageEventInterface::class);
    }

    /**
     * @param ImageInterface|\PhpSpec\Wrapper\Collaborator $image
     */
    public function it_should_return_image(ImageInterface $image)
    {
        $this->beConstructedWith($image);
        $this->getImage()->shouldReturn($image);
    }
}
