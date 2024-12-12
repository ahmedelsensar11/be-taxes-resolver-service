<?php

namespace App\Tests\Unit\Registry;

use App\Registry\TaxProviderRegistry;
use App\TaxProviders\SeriousTax;
use App\TaxProviders\BeeTax;
use PHPUnit\Framework\TestCase;

class TaxProviderRegistryTest extends TestCase
{
    private TaxProviderRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new TaxProviderRegistry();
    }

    public function testGetTaxClassReturnsBeeTaxClass(): void
    {
        $result = $this->registry->getClass('Bee');
        $this->assertEquals(BeeTax::class, $result);
    }

    public function testGetTaxClassReturnsSeriousTaxClass(): void
    {
        $result = $this->registry->getClass('Serious');
        $this->assertEquals(SeriousTax::class, $result);
    }

    public function testGetTaxClassThrowsExceptionForInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid tax provider: INVALID');
        $this->registry->getClass('INVALID');
    }
}