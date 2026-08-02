<?php

namespace Tests\Feature;

use App\Services\Est8ads\Discovery\ListingPageScraper;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class ListingPageScraperPriceTest extends TestCase
{
    #[DataProvider('prices')]
    public function test_it_reads_the_asking_price_from_page_text(string $text, ?float $expected): void
    {
        $method = new ReflectionMethod(ListingPageScraper::class, 'parsePrice');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke(app(ListingPageScraper::class), $text));
    }

    public static function prices(): array
    {
        return [
            // Regression: a price followed by another figure used to be read as
            // one number, turning 390,000 into 3,900,002.60.
            'price followed by a second amount' => ['€390,000 2.60 per month', 390000.0],
            'space grouped thousands' => ['Price: 390 000 € plus 2,60 € fee', 390000.0],
            'european dot thousands' => ['EUR 699.000', 699000.0],
            'anglo comma thousands' => ['1,499,000 EUR', 1499000.0],
            // The repeated amount is the asking price; the teaser is not.
            'teaser price loses to repeated price' => ['from €99,000 · asking €480,000 · €480,000', 480000.0],
            'no price at all' => ['Contact us for details', null],
            'amount below the plausible floor is ignored' => ['Agency fee €500', null],
        ];
    }
}
