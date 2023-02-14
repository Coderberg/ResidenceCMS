<?php

declare(strict_types=1);

namespace App\Transformer;

use Symfony\Component\HttpFoundation\Request;

final class RequestToArrayTransformer
{
    public function transform(Request $request): array
    {
        return [
            'city' => $request->query->getInt('city', 0),
            'deal_type' => $request->query->getInt('deal_type', 0),
            'category' => $request->query->getInt('category', 0),
            'bedrooms' => $request->query->getInt('bedrooms', 0),
            'guests' => $request->query->getInt('guests', 0),
            'feature' => $request->query->getInt('feature', 0),
            'sort_by' => $request->query->get('sort_by', 'priority_number'),
            'state' => $request->query->get('state', 'published'),
            'page' => $request->query->getInt('page', 1),
        ];
    }
}
