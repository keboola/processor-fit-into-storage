<?php

declare(strict_types=1);

namespace Keboola\ProcessorCsvWrap;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ConfigDefinition extends BaseConfigDefinition
{
    protected function getParametersDefinition(): ArrayNodeDefinition
    {
        $parametersNode = parent::getParametersDefinition();
        // @formatter:off
        /** @noinspection NullPointerExceptionInspection */
        $parametersNode
            ->children()
                ->integerNode('chunk_size')->defaultValue(16000000)->end()
            ->end()
        ;
        // @formatter:on
        return $parametersNode;
    }
}
