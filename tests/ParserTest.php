<?php

namespace kesselb\bankleitzalen\tests;

use kesselb\bankleitzahlen\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testSourceFileNotReadable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$sourceFile is not readable');
        new Parser(__DIR__ . '/parser/missing-data.txt', __DIR__ . '/test');
    }

    public function testDataDirectoryNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$dataDirectory do not exist or is not writeable');
        new Parser(__DIR__ . '/parser/_data.txt', __DIR__ . '/test');
    }

    public function testParse(): void
    {
        $parser = new Parser(__DIR__ . '/parser/_data.txt', __DIR__ . '/parser');
        $parser->parse();

        $this->assertStringEqualsFile(__DIR__ . '/parser/code-1.php', "<?php\n\nreturn array (\n  10000000 => 'MARKDEF1100',\n);");
        $this->assertStringEqualsFile(__DIR__ . '/parser/code-2.php', "<?php\n\nreturn array (\n  20000000 => 'MARKDEF1200',\n);");
        $this->assertStringEqualsFile(__DIR__ . '/parser/name-1.php', "<?php\n\nreturn array (\n  10000000 => 'Bundesbank',\n);");
        $this->assertStringEqualsFile(__DIR__ . '/parser/name-2.php', "<?php\n\nreturn array (\n  20000000 => 'Bundesbank',\n);");
    }
}
