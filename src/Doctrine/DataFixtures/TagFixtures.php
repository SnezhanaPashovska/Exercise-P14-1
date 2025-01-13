<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create an array of 25 indexes, we don't need nulls
        $tags = range(0, 24);

        // Use array_map with a callback to generate Tag instances
        $tags = array_map(
            static fn (int $index): Tag => (new Tag())->setName(sprintf('Tag %d', $index)),
            $tags
        );

        // Persist each tag using array_walk
        array_walk($tags, static fn (Tag $tag) => $manager->persist($tag));

        // Flush to the database
        $manager->flush();
    }
}
