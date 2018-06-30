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
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UserExistValidator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserExistValidator extends ConstraintValidator implements UserExistValidatorInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        TranslatorInterface $translator,
        UserRepositoryInterface $userRepository
    ) {
        $this->translator = $translator;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->userRepository->getUserByUsernameAndEmail($value->username, $value->email)) {
            $this->context->buildViolation(
                $this->translator->trans($constraint->message, [], 'validators')
            )->addViolation();
        }
    }
}
