<?php

declare(strict_types=1);

namespace kesselb\bankleitzahlen;

class BicCode extends Reader
{
    /**
     * @inheritDoc
     */
    protected function getDataFilePath(string $index): string
    {
        return $this->dataDirectory . '/code-' . $index . '.php';
    }
}
