<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 30.08.2018
 * Time: 14:07.
 */

namespace App\Tests\Unit\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

final class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSlugify(string $string, string $slug, string $slugIntl): void
    {
        if (\function_exists('transliterator_transliterate')) {
            $this->assertSame($slugIntl, Slugger::slugify($string));
        } else {
            $this->assertSame($slug, Slugger::slugify($string));
        }
    }

    private function getSlugs(): \Generator
    {
        yield ['Lorem Ipsum', 'lorem-ipsum', 'lorem-ipsum'];
        yield ['Lorem Ipsum!', 'lorem-ipsum', 'lorem-ipsum'];
        yield ['Русский текст', 'russkii-tekst', 'russkij-tekst'];
        yield [' Русский текст !!!', 'russkii-tekst', 'russkij-tekst'];
        yield ['Lorem Ipsum №69!', 'lorem-ipsum-69', 'lorem-ipsum-no69'];
        yield ['Magyar nyelvű szöveg', 'magyar-nyelvu-szoveg', 'magyar-nyelvu-szoveg'];
    }
}
