<?php

declare(strict_types=1);

namespace App\Transformer;

use Symfony\Component\HttpFoundation\Request;

final class RequestToArrayTransformer
{
    public function transform(Request $request): array
    {
        return [
            'city' => $request->query->getInt('city'),
            'deal_type' => $request->query->getInt('deal_type'),
            'category' => $request->query->getInt('category'),
            'bedrooms' => $request->query->getInt('bedrooms'),
            'guests' => $request->query->getInt('guests'),
            'feature' => $request->query->getInt('feature'),
            'sort_by' => $request->query->get('sort_by', 'priority_number'),
            'state' => $request->query->get('state', 'published'),
            'page' => $request->query->getInt('page', 1),
        ];
    }
}
