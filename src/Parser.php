<?php

declare(strict_types=1);

namespace kesselb\bankleitzahlen;

use InvalidArgumentException;

class Parser
{
    /** @var string */
    private $sourceFile;
    /** @var string */
    private $targetDirectory;

    /** @var int */
    private $index = 1;
    /** @var array */
    private $names = [];
    /** @var array */
    private $codes = [];

    /**
     * Parser constructor.
     * @param $sourceFile
     * @param $dataDirectory
     */
    public function __construct(string $sourceFile, string $dataDirectory)
    {
        if (!is_readable($sourceFile)) {
            throw new InvalidArgumentException('$sourceFile is not readable');
        }

        if (!file_exists($dataDirectory) || !is_writable($dataDirectory)) {
            throw new InvalidArgumentException('$dataDirectory do not exist or is not writeable');
        }

        $this->sourceFile = $sourceFile;
        $this->targetDirectory = $dataDirectory;
    }


    public function parse(): void
    {
        $handle = fopen($this->sourceFile, 'rb');

        while (($buffer = fgets($handle)) !== false) {
            $bankCode = substr($buffer, 0, 8);
            $isHidden = $buffer[8] === '2';
            $bankName = trim(mb_convert_encoding(substr($buffer, 9, 58), 'UTF-8', 'WINDOWS-1252'));
            $swiftBic = trim(substr($buffer, 139, 11));
            $newIndex = (int)$bankCode[0];

            if ($isHidden) {
                continue;
            }

            if ($newIndex !== $this->index) {
                $this->write();
                $this->reset($newIndex);
            }

            $this->names[$bankCode] = $bankName;
            if ($swiftBic !== '') {
                $this->codes[$bankCode] = $swiftBic;
            }
        }

        fclose($handle);

        $this->write();
        $this->reset();
    }

    protected function write(): void
    {
        $names = "<?php\n\nreturn " . var_export($this->names, true) . ';';
        if (file_put_contents($this->targetDirectory . '/name-' . $this->index . '.php', $names) === false) {
            throw new \RuntimeException('Something went wrong');
        }

        $codes = "<?php\n\nreturn " . var_export($this->codes, true) . ';';
        if (file_put_contents($this->targetDirectory . '/code-' . $this->index . '.php', $codes) === false) {
            throw new \RuntimeException('Something went wrong');
        }
    }

    protected function reset(int $newIndex = 1): void
    {
        $this->index = $newIndex;
        $this->names = [];
        $this->codes = [];
    }
}
