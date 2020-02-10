<?php

declare(strict_types=1);

namespace kesselb\bankleitzahlen;

class BankName extends Reader
{
    /**
     * @inheritDoc
     */
    protected function getDataFilePath(string $index): string
    {
        return $this->dataDirectory . '/name-' . $index . '.php';
    }
}
