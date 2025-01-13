<?php

declare(strict_types=1);

namespace App\List\VideoGameList;

use App\Model\ValueObject\Direction;
use App\Model\ValueObject\Sorting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('pagination')]
final class PaginationValueResolver implements ValueResolverInterface
{
    /**
     * @return iterable<Pagination>
     */

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        // Ensure we're resolving the Pagination type
        if (Pagination::class !== $argumentType) {
            return [];
        }

        // Create Pagination instance using parameters from the request
        return [
            new Pagination(
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 10),
                Sorting::tryFromName($request->query->get('sorting', 'releaseDate')) ?? Sorting::ReleaseDate,
                Direction::tryFromName($request->query->get('direction', 'desc')) ?? Direction::Descending
            ),
        ];
    }
}
