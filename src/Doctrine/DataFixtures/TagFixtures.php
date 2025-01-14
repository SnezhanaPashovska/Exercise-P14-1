<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = range(0, 24);

        $tags = array_map(
            static fn (int $index): Tag => (new Tag())->setName(sprintf('Tag %d', $index)),
            $tags
        );

        array_walk($tags, static fn (Tag $tag) => $manager->persist($tag));

        $manager->flush();
    }
}
