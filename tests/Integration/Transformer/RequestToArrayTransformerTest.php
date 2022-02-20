<?php

declare(strict_types=1);

namespace App\Tests\Integration\Transformer;

use App\Transformer\RequestToArrayTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RequestToArrayTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $request = new Request();
        $request->query->set('city', 3);
        $request->query->set('deal_type', 2);
        $request->query->set('category', 4);
        $request->query->set('bedrooms', 5);
        $request->query->set('page', 2);

        $transformer = new RequestToArrayTransformer();

        $array = $transformer->transform($request);

        $this->assertSame($array['city'], 3);
        $this->assertSame($array['deal_type'], 2);
        $this->assertSame($array['category'], 4);
        $this->assertSame($array['bedrooms'], 5);
        $this->assertSame($array['page'], 2);
    }
}
