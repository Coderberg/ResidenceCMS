<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\HtmlHelper;
use PHPUnit\Framework\TestCase;

final class HtmlHelperTest extends TestCase
{
    /**
     * @dataProvider getHtml
     */
    public function testTtml2Text(string $htmlString): void
    {
        $this->assertSame(
            'Lorem ipsum dolor sit amet',
            HtmlHelper::html2Text($htmlString)
        );
    }

    /**
     * @dataProvider getText
     */
    public function testText2Html(string $text, string $html): void
    {
        $this->assertSame($html, HtmlHelper::text2Html($text));
    }

    private function getHtml(): \Generator
    {
        yield ['<div><h1>Lorem</h1> <p>ipsum <strong>dolor</strong> sit amet</p></div>'];
        yield ['Lorem <a href="https://localhost">ipsum</a> dolor sit amet'];
        yield ['Lorem <script>ipsum</script> dolor sit amet'];
        yield ['Lorem ipsum dolor sit amet'];
        yield ['Lorem ipsum <span class="text-danger">dolor</span> sit amet'];
    }

    private function getText(): \Generator
    {
        yield ["Lorem ipsum\r dolor sit amet", 'Lorem ipsum<br> dolor sit amet'];
        yield ["Lorem\n ipsum dolor\r sit amet", 'Lorem<br> ipsum dolor<br> sit amet'];
        yield ["Lorem\r\n ipsum dolor sit amet", 'Lorem<br> ipsum dolor sit amet'];
    }
}
