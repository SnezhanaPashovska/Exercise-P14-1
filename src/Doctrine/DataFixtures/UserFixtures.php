<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create an array of 25 indexes, which will be populated using the callback
        $users = range(0, 24);

        // Map the array of indexes to actual User instances using a callback
        $users = array_map(
            static fn (int $index): User => (new User())
                ->setEmail(sprintf('user+%d@email.com', $index))
                ->setPlainPassword('password')
                ->setUsername(sprintf('user+%d', $index)),
            $users
        );

        // Persist each User instance using array_walk
        array_walk($users, static fn (User $user) => $manager->persist($user));

        // Ensure the changes are flushed to the database
        $manager->flush();
    }
}
