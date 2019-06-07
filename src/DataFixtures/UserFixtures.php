<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user ->setEmail('admin@test.com');
        $user->agreeToTerms();
        $user->setRoles(['ROLE_ADMIN']);
        $user ->setPassword(
            $this->encoder->encodePassword($user, '1111')
        );

        $apiToken1 = new ApiToken($user);
        $apiToken2 = new ApiToken($user);
        $manager->persist($apiToken1);
        $manager->persist($apiToken2);

        $manager->persist($user);
        $manager->flush();
    }
}
