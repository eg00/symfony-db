<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\UniqueGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppUserFixture extends Fixture
{
    protected const ADMIN_EMAIL = 'user@dot.com';
    protected const DEFAULT_PASSWORD = 'password';
    protected const USER_COUNT = 10;

    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var Generator|UniqueGenerator
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public static function getReferenceKey($i)
    {
        return sprintf('user_%s', $i);
    }

    public function load(ObjectManager $manager): void
    {
       $this->createAdmin($manager);
       $this->createUsers($manager);
        $manager->flush();
    }

    protected function createUsers(ObjectManager $manager): void
    {
        for ($i = self::USER_COUNT; $i >= 0; $i--)  {
            $user = (new User())
                ->setName($this->faker->name)
                ->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::DEFAULT_PASSWORD));

            $manager->persist($user);
            $this->addReference(self::getReferenceKey($i), $user);
        }
        $manager->flush();
    }

    protected function createAdmin(ObjectManager $manager): void
    {
        $user = (new User())
            ->setName($this->faker->name)
            ->setEmail(self::ADMIN_EMAIL)
            ->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, self::DEFAULT_PASSWORD));

        $manager->persist($user);
        $manager->flush();
    }

}
