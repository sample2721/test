<?php

class parse_logTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider readDataProvider
     */
    public function read(string $filename, array $expected)
    {
        $actual = read($filename);
        $this->assertEquals($expected, $actual);
    }

    public function readDataProvider()
    {
        return [
            [__DIR__ . '/../resources/log1', ['2020-06-29','00:27:23','169.44.140.62','https://from.com','http://host6.ru/',
                '2020-06-29','01:09:23','157.176.91.81','http://high.ru','http://host5.ru/']],
            [__DIR__ . '/../resources/log2', ['169.44.140.62','Opera','Windows','157.176.91.81','Edge','Windows']]
        ];
    }

    /**
     * @test
     * @dataProvider buildParamsDataProvider
     */
    public function buildParams(int $fieldsCount, int $valuesCount, $expected)
    {
        $actual = buildParams($fieldsCount, $valuesCount);
        $this->assertEquals($expected, $actual);
    }

    public function buildParamsDataProvider()
    {
        return [
            [2, 2, '($1,$2)'],
            [2, 4, '($1,$2),($3,$4)']
        ];
    }

    /**
     * @test
     * @dataProvider buildParamsWithExceptionDataProvider
     */
    public function buildParamsWithException(int $fieldsCount, int $valuesCount)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('fieldsCount or valuesCount must be grate zero');

        buildParams($fieldsCount, $valuesCount);
    }

    public function buildParamsWithExceptionDataProvider()
    {
        return [
            [0, 1], [1, 0], [0, 0]
        ];
    }

}