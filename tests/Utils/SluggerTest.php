<?php
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 30.08.2018
 * Time: 14:07.
 */

namespace App\Tests\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSlugify(string $string, string $slug)
    {
        $this->assertSame($slug, Slugger::slugify($string));
    }

    public function getSlugs()
    {
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield ['Lorem Ipsum!', 'lorem-ipsum'];
        yield ['Русский текст', 'russkiy-tekst'];
        yield [' Русский текст !!!', 'russkiy-tekst'];
        yield ['Lorem Ipsum №69!', 'lorem-ipsum-69'];
    }
}
