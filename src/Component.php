<?php

declare(strict_types=1);

namespace Keboola\ProcessorCsvWrap;

use Keboola\Component\BaseComponent;
use Keboola\Csv\CsvWriter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Component extends BaseComponent
{
    public function run(): void
    {
        $finder = new Finder();
        $finder->in($this->getDataDir() . '/in/tables')->files();
        $this->processDir($finder, 'tables');
        $finder = new Finder();
        $finder->in($this->getDataDir() . '/in/files')->files();
        $this->processDir($finder, 'files');
    }

    private function processDir(Finder $finder, string $dir) : void
    {
        $fs = new Filesystem();
        foreach ($finder as $inFile) {
            if ($inFile->getExtension() === 'manifest') {
                // copy manifest without modification
                $fs->copy($inFile->getPathname(), $this->getDataDir() . "/out/$dir/" . $inFile->getFilename());
            } else {
                $destinationDir = $this->getDataDir() . "/out/$dir/" . $inFile->getRelativePath();
                $fs->mkdir($destinationDir);
                $destinationFile = $destinationDir . '/' . $inFile->getFilename();
                $this->processFile($inFile, $destinationFile);
            }
        }
    }

    private function processFile(SplFileInfo $inFile, string $destinationFileName) : void
    {
        /** @var Config $config */
        $config = $this->getConfig();
        $chunkSize = $config->getChunkSize();
        $csvFile = new CsvWriter($destinationFileName);
        $csvFile->writeRow(['contents', 'file', 'index']);
        ChunkedWriter::processFile($inFile->getPathname(), $csvFile, $chunkSize, $inFile->getFilename());
    }

    protected function getConfigClass(): string
    {
        return Config::class;
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}
