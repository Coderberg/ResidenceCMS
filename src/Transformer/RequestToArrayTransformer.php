<?php

declare(strict_types=1);

namespace App\Transformer;

use Symfony\Component\HttpFoundation\Request;

class RequestToArrayTransformer
{
    public function transform(Request $request): array
    {
        $params = [];
        $params['city'] = (int) ($request->query->get('city', 0));
        $params['deal_type'] = (int) ($request->query->get('deal_type', 0));
        $params['category'] = (int) $request->query->get('category', 0);
        $params['bedrooms'] = (int) $request->query->get('bedrooms', 0);
        $params['guests'] = (int) $request->query->get('guests', 0);
        $params['page'] = (int) $request->attributes->get('page', 1);

        return $params;
    }
}
