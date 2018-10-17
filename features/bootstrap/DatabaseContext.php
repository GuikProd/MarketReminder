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

use App\Domain\Models\User;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class DatabaseContext.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class DatabaseContext implements Context
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * DatabaseContext constructor.
     *
     * @param RegistryInterface       $doctrine
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        RegistryInterface $doctrine,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->doctrine = $doctrine;
        $this->encoderFactory = $encoderFactory;
        $this->schemaTool = new SchemaTool($this->doctrine->getManager());
    }

    /**
     * @BeforeScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function clearDatabase()
    {
        $this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
        $this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * Launch Doctrine fixtures command.
     *
     * @param TableNode $users
     *
     * @Given /^I load following users:$/
     *
     * @throws Exception
     */
    public function iLoadFollowingUsers(TableNode $users)
    {
        foreach ($users->getHash() as $hash) {
            $userDTO = new UserRegistrationDTO(
                $hash['username'],
                $hash['email'],
                $hash['plainPassword']
            );

            $user = new User(
                $userDTO->email,
                $userDTO->username,
                $this->encoderFactory->getEncoder(User::class)->encodePassword($hash['plainPassword'], null)
            );

            if ($hash['validated']) {
                $user->validate();
            }

            if ($hash['resetPasswordToken']) {
                $userResetPasswordToken = new UserResetPasswordToken($hash['resetPasswordToken']);

                $user->askForPasswordReset($userResetPasswordToken);
            }

            if ($hash['validationToken']) {
                $user->renewValidationToken($hash['validationToken']);
            }

            $this->doctrine->getManager()->persist($user);
        }

        $this->doctrine->getManager()->flush();
    }
}
