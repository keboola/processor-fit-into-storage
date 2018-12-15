<?php

declare(strict_types=1);

namespace Keboola\ProcessorCsvWrap;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public function getChunkSize(): int
    {
        return (int) $this->getValue(['parameters', 'chunk_size']);
    }
}
