<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\Neighborhood;
use App\Repository\FilterRepository;
use App\Transformer\RequestToArrayTransformer;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

final class NeighborhoodService
{
    /**
     * @var RequestToArrayTransformer
     */
    private $transformer;

    /**
     * @var FilterRepository
     */
    private $repository;

    public function __construct(RequestToArrayTransformer $transformer, FilterRepository $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;
    }

    public function getSearchParams(Request $request, Neighborhood $neighborhood): array
    {
        $searchParams = $this->transformer->transform($request);
        $searchParams['neighborhood'] = $neighborhood->getId();

        return $searchParams;
    }

    public function getProperties(array $searchParams): PaginationInterface
    {
        return $this->repository->findByFilter($searchParams);
    }

    public function decorateOptions(array $siteOptions, Neighborhood $neighborhood): array
    {
        $siteOptions['title'] = $neighborhood->getCity() ?? $siteOptions['title'];

        return $siteOptions;
    }
}
