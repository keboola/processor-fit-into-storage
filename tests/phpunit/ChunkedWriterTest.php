<?php

declare(strict_types=1);

namespace Keboola\ProcessorCsvWrap\Tests;

use Keboola\Csv\CsvWriter;
use Keboola\ProcessorCsvWrap\ChunkedWriter;
use Keboola\Temp\Temp;
use PHPUnit\Framework\TestCase;

class ChunkedWriterTest extends TestCase
{
    public function testWriteExact(): void
    {
        $temp = new Temp();
        $temp->initRunFolder();
        $source = $temp->getTmpFolder() . DIRECTORY_SEPARATOR . uniqid('src-');
        file_put_contents($source, 'abcd');
        $target = $temp->getTmpFolder() . DIRECTORY_SEPARATOR . uniqid('csv-');
        $writer = new CsvWriter($target);
        ChunkedWriter::processFile($source, $writer, 2, 'id');
        $data = file_get_contents($target);
        self::assertEquals(
            implode("\n", ['"ab","id"', '"cd","id"', '']),
            $data
        );
    }

    public function testWriteMb(): void
    {
        $temp = new Temp();
        $temp->initRunFolder();
        $source = $temp->getTmpFolder() . DIRECTORY_SEPARATOR . uniqid('src-');
        file_put_contents($source, 'šýžčěšýžčěšýžčě');
        $target = $temp->getTmpFolder() . DIRECTORY_SEPARATOR . uniqid('csv-');
        $writer = new CsvWriter($target);
        ChunkedWriter::processFile($source, $writer, 2, 'id');
        $data = file_get_contents($target);
        self::assertEquals(
            implode(
                "\n",
                ['"šý","id"', '"žč","id"', '"ěš","id"', '"ýž","id"', '"čě","id"', '"šý","id"', '"žč","id"', '"ě","id"',
                    '']
            ),
            $data
        );
    }
}
