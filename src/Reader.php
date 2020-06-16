<?php

declare(strict_types=1);

namespace kesselb\bankleitzahlen;

use InvalidArgumentException;
use RuntimeException;

abstract class Reader
{
    /** @var string */
    protected $dataDirectory;

    /**
     * @param string $index
     * @return string
     */
    abstract protected function getDataFilePath(string $index): string;

    /**
     * Name constructor.
     * @param string $dataDirectory
     */
    public function __construct(string $dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
    }

    /**
     * @param int $bankleitzahl
     * @return mixed
     */
    public function byBankleitzahl(int $bankleitzahl)
    {
        if ($bankleitzahl < 10000000 || $bankleitzahl > 89999999) {
            throw new InvalidArgumentException('Invalid bankleitzahl');
        }

        $index = ((string)$bankleitzahl)[0];
        $banks = @include $this->getDataFilePath($index);

        if ($banks === false) {
            throw new InvalidArgumentException('Invalid bankleitzahl');
        }

        if (!array_key_exists($bankleitzahl, $banks)) {
            throw new RuntimeException('There is no such bankleitzahl');
        }

        return $banks[$bankleitzahl];
    }

    /**
     * @param string $iban
     * @return mixed
     */
    public function byIBAN(string $iban)
    {
        $matches = [];

        if (preg_match('/DE\d{2}(\d{8})/', $iban, $matches) !== 1) {
            throw new InvalidArgumentException('IBAN is not supported');
        }

        return $this->byBankleitzahl((int)$matches[1]);
    }
}
