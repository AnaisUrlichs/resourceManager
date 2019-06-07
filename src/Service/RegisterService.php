<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegisterService

{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RegisterService constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * Register new User in the database
     *
     * @param User $user
     * @param string $password
     * @param bool $agreeTerms
     */
    public function registerUser(User $user, string $password, bool $agreeTerms)
    {
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $password
            //$request->request->get('password')
            ));

            // be absolutely sure they agree
            if (true === $agreeTerms) {
                $user->agreeToTerms();
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }
}