<?php
declare(strict_types = 1);

namespace App\Tests\Converter;

use App\Converter\MarsClockConverter;
use PHPUnit\Framework\TestCase;

final class SearchEngineTest extends TestCase
{
    /**
     * @dataProvider dateDataProvider
     */
    public function testConverter(string $eatrhDate, string $marsSolDate, string $martianCoordinatedTime): void
    {
        $converter = new MarsClockConverter(new \DateTime($eatrhDate));

        $this->assertEquals($marsSolDate, $converter->getMarsSolDate());
        $this->assertEquals($martianCoordinatedTime, $converter->getMartianCoordinatedTime());
    }

    public function dateDataProvider(): array
    {
        return [
            [
                'earthDate' => '2020-02-01 19:30:00',
                'marsSolDate' => '51931.64451235686',
                'martianCoordinatedTime' => '15:28:05',
            ],
            [
                'earthDate' => '2000-01-01 00:00:00',
                'marsSolDate' => '44791.13359524576',
                'martianCoordinatedTime' => '03:12:22',
            ],
        ];
    }
}
