<?php

declare(strict_types=1);

namespace Keboola\ProcessorCsvWrap;

use Keboola\Csv\CsvWriter;

class ChunkedWriter
{
    public static function processFile(string $sourcePath, CsvWriter $writer, int $chunkSize, string $id): void
    {
        $fh = @fopen($sourcePath, 'r');
        if ($fh === false) {
            throw new \Exception(sprintf('Cannot open source file "%s".', $sourcePath));
        }
        $buf = '';
        while (!feof($fh)) {
            $buf .= fread($fh, $chunkSize * 4);
            while (mb_strlen($buf) >= $chunkSize) {
                $slice = mb_substr($buf, 0, $chunkSize);
                $writer->writeRow([$slice, $id]);
                $buf = mb_substr($buf, $chunkSize);
            }
        }
        if ($buf) {
            $writer->writeRow([$buf, $id]);
        }
    }
}
