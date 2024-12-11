<?php

namespace App\Tests\Unit\Service;

use App\Enum\TaxTypeEnum;
use App\Service\TaxTypeResolver;
use PHPUnit\Framework\TestCase;

class TaxTypeResolverTest extends TestCase
{
    private TaxTypeResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new TaxTypeResolver();
    }

    public function testResolveReturnsBeeType(): void
    {
        $result = $this->resolver->resolve('CA');
        $this->assertEquals($result, TaxTypeEnum::BEE->value);
    }

    public function testResolveReturnsSeriousType(): void
    {
        $result = $this->resolver->resolve('EE');
        $this->assertEquals(TaxTypeEnum::SERIOUS->value, $result);
    }

    public function testResolveThrowsExceptionForUnsupportedCountry(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No tax provider available for country: XYZ');
        $this->resolver->resolve('XYZ');
    }
}