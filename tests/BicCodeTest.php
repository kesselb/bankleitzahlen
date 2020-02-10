<?php

namespace kesselb\bankleitzalen\tests;

use kesselb\bankleitzahlen\BankName;
use kesselb\bankleitzahlen\BicCode;
use PHPUnit\Framework\TestCase;

class BicCodeTest extends TestCase
{
    /** @var BicCode */
    protected $finder;

    public function setUp(): void
    {
        $this->finder = new BicCode(__DIR__ . '/data');
    }

    public function testNoSuchBankleitzahl(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectDeprecationMessage('There is no such bankleitzahl');
        $this->finder->byBankleitzahl('10000001');
    }

    /**
     * @dataProvider providerInvalidBankleitzahl
     * @param string $bankleitzahl
     */
    public function testInvalidBankleitzahl(string $bankleitzahl): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectDeprecationMessage('Invalid bankleitzahl');
        $this->finder->byBankleitzahl($bankleitzahl);
    }

    public function providerInvalidBankleitzahl(): array
    {
        return [
            ['999999'],
            ['20000000'],
            ['80000000']
        ];
    }

    public function testByBankleitzahl()
    {
        $this->assertEquals('MARKDEF1100', $this->finder->byBankleitzahl('10000000'));
    }

    public function testNoSuchBankleitzahlByIBAN()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectDeprecationMessage('There is no such bankleitzahl');
        $this->finder->byIBAN('DE00100000011234567890');
    }

    public function testIbanNotSupportedByIBAN()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectDeprecationMessage('IBAN is not supported');
        $this->finder->byIBAN('DE00100000');
    }

    /**
     * @dataProvider providerByIBAN
     */
    public function testByIBAN($iban, $expected)
    {
        $this->assertEquals($expected, $this->finder->byIBAN($iban));
    }

    public function providerByIBAN(): array
    {
        return [
            'IBAN' => ['DE00100000001234567890', 'MARKDEF1100'],
            'IBAN without Account' => ['DE0010000000', 'MARKDEF1100'],
        ];
    }
}
