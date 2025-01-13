<?php

declare(strict_types=1);

namespace App\List\VideoGameList;

use App\Model\Entity\Tag;

final class Filter
{
    /**
     * @var Tag[]
     */
    private array $tags = [];

    /**
     * @param Tag[] $tags
     */
    public function __construct(
        private ?string $search = null,
        array $tags = [], // Allow an empty array by default
    ) {
        $this->tags = $tags;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): Filter
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @return Tag[] the tags associated with the filter
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags the tags to set for the filter
     */
    public function setTags(array $tags): Filter
    {
        $this->tags = $tags;

        return $this;
    }
}
