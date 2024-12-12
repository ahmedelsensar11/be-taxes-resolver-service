<?php

namespace App\Tests\Unit\Factory;

use App\DTO\TaxDTO;
use App\Factories\TaxFactory;
use App\Registry\TaxProviderRegistry;
use App\Service\TaxTypeResolver;
use App\TaxProviders\BeeTax;
use PHPUnit\Framework\TestCase;

class TaxFactoryTest extends TestCase
{
    private TaxTypeResolver $mockResolver;
    private TaxProviderRegistry $mockRegistry;
    private TaxFactory $factory;

    protected function setUp(): void
    {
        $this->mockResolver = $this->createMock(TaxTypeResolver::class);
        $this->mockRegistry = $this->createMock(TaxProviderRegistry::class);

        // Create the factory with mocked dependencies
        $this->factory = new TaxFactory($this->mockResolver, $this->mockRegistry);
    }

    public function testMakeReturnsBeeTaxInstance(): void
    {
        $dto = new TaxDTO(country_code: 'US', state: 'CA', city: 'Los Angeles', street: 'Main St', postcode: '90001');

        $this->mockResolver->method('resolve')->with('US')->willReturn('Bee');
        $this->mockRegistry->method('getClass')->with('Bee')->willReturn(BeeTax::class);

        // Act
        $result = $this->factory->make($dto);

        // Assert
        $this->assertInstanceOf(BeeTax::class, $result);
    }

    public function testMakeThrowsExceptionForInvalidCountry(): void
    {
        $dto = new TaxDTO(country_code: 'XYZ', state: 'CA', city: 'Los Angeles', street: 'Main St', postcode: '90001');

        $this->mockResolver->method('resolve')->with('XYZ')->willThrowException(new \InvalidArgumentException('No tax provider available for country: XYZ'));

        // Assert exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No tax provider available for country: XYZ');

        // Act
        $this->factory->make($dto);
    }

    public function testMakeThrowsExceptionForInvalidTaxType(): void
    {
        $dto = new TaxDTO(country_code: 'US', state: 'CA', city: 'Los Angeles', street: 'Main St', postcode: '90001');

        $this->mockResolver->method('resolve')->with('US')->willReturn('INVALID');
        $this->mockRegistry->method('getClass')->with('INVALID')->willThrowException(new \InvalidArgumentException('Invalid tax provider: INVALID'));

        // Assert exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid tax provider: INVALID');

        // Act
        $this->factory->make($dto);
    }
}