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

namespace App\Application\Validator;

use App\Application\Validator\Interfaces\UserExistValidatorInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UserExistValidator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserExistValidator extends ConstraintValidator implements UserExistValidatorInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @inheritdoc
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritdoc
     */
    public function validate($value, Constraint $constraint)
    {
        $user = $this->userRepository->getUserByUsernameAndEmail($value->username, $value->email);

        if (!$constraint instanceof UserExist) {
            throw new \LogicException(sprintf(''));
        }

        if ($constraint->exist && $user instanceof UserInterface) {
            $this->context->buildViolation($constraint->message)
                          ->setTranslationDomain('validators')
                          ->addViolation();
        }

        if (!$constraint->exist && \is_null($user)) {
            $this->context->buildViolation($constraint->message)
                          ->setTranslationDomain('validators')
                          ->addViolation();
        }
    }
}
