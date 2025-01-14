<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = range(0, 24);

        $users = array_map(
            static fn(int $index): User => (new User())
                ->setEmail(sprintf('user+%d@email.com', $index))
                ->setPlainPassword('password')
                ->setUsername(sprintf('user+%d', $index)),
            $users
        );

        array_walk($users, static fn(User $user) => $manager->persist($user));

        $manager->flush();
    }
}
