<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        //$this->addSeries(50);
        $this->addUsers(20);
    }

    private function addSeries(int $number = 30)
    {
        for ($i = 0; $i < $number; $i++) {
            $serie = new Serie();
            $serie
                ->setName(implode($this->faker->words(3)))
                ->setVote($this->faker->numberBetween(0, 10))
                ->setStatus($this->faker->randomElement(["ended", "returning", "canceled"]))
                ->setPoster("poster.png")
                ->setBackdrop("backdrop.png")
                ->setTmdbId(123)
                ->setPopularity(300)
                ->setFirstAirDate($this->faker->dateTimeBetween("-6 months"))
                ->setLastAirDate($this->faker->dateTimeBetween($serie->getFirstAirDate()))
                ->setGenres($this->faker->randomElement(["Western","Comedy", "Drama"]));

            $this->entityManager->persist($serie);
        }

        $this->entityManager->flush();
    }

    private function addUsers(int $number = 5)
    {
        for ($i = 0; $i < $number; $i++) {
            $user = new User();
            $user
                ->setRoles(['ROLE_USER'])
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName);

            $password = $this->passwordHasher->hashPassword($user,'123');

            $user->setPassword($password);

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
