<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Repository\FilterRepository;
use App\Transformer\RequestToArrayTransformer;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AccountService
{
    /**
     * @var FilterRepository
     */
    private $repository;

    /**
     * @var RequestToArrayTransformer
     */
    private $transformer;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        FilterRepository $repository,
        RequestToArrayTransformer $transformer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
        $this->tokenStorage = $tokenStorage;
    }

    public function getUserProperties(Request $request): PaginationInterface
    {
        $searchParams = $this->transformer->transform($request);
        $searchParams['user'] = $this->tokenStorage->getToken()->getUser()->getId();
        $searchParams['sort_by'] = 'id';

        return $this->repository->findByFilter($searchParams);
    }
}
