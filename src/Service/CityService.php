<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Repository\FilterRepository;
use App\Transformer\RequestToArrayTransformer;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

final class CityService
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

    public function getSearchParams(Request $request, City $city): array
    {
        $searchParams = $this->transformer->transform($request);
        $searchParams['city'] = $city->getId();

        return $searchParams;
    }

    public function getProperties(array $searchParams): PaginationInterface
    {
        return $this->repository->findByFilter($searchParams);
    }

    public function decorateOptions(array $siteOptions, City $city): array
    {
        $siteOptions['title'] = $city->getTitle() ?? $siteOptions['title'];
        $siteOptions['meta_title'] = $city->getMetaTitle() ?? $city->getName();
        $siteOptions['meta_description'] = $city->getMetaDescription() ?? $siteOptions['meta_description'];

        return $siteOptions;
    }
}
