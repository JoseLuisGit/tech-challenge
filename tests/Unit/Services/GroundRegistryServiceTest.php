<?php

use App\Models\GroundRegistry;
use App\Services\GroundRegistryService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GroundRegistryServiceTest extends TestCase
{

    protected GroundRegistryService $groundRegistryService;
    public function setUp(): void
    {
        parent::setUp();
        Log::shouldReceive('warning');
        $this->groundRegistryService = $this->app->make(GroundRegistryService::class);
    }

    private function makeMockGroundRegistry(array $data = [])
    {
        $groundRegistry = $this->partialMock(GroundRegistry::class)->makePartial();
        $groundRegistry->ground_value = $data['ground_value'] ?? 0;
        $groundRegistry->ground_surface = $data['ground_surface'] ?? 0;
        $groundRegistry->subsidy = $data['subsidy'] ?? 0;
        $groundRegistry->construction_surface = $data['construction_surface'] ?? 0;
        $groundRegistry->street_number = $data['street_number'] ?? 'no number';
        $groundRegistry->id = $data['id'];
        return $groundRegistry;

    }

    public function test_getSumPriceUnit()
    {
        // Arrange
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 10.4, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 200, 'ground_surface' => 20.4, 'construction_surface' => 254.5, 'subsidy' => 4.45]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 250, 'ground_surface' => 30.4, 'construction_surface' => 354.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 350, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 9.45]),
        ]);

        // Act
        list($priceUnitResult, $priceUnitConstructionResult) = $this->groundRegistryService->getPriceUnitEvals($groundRegistries);

        // Assert
        $expectedResult = [
            'price_units' => [4.165384615384615,5.3539215686274515,2.7736842105263166, -0.7866336633663362],
            'price_unit_construcctions' => [9.40576923076923,8.025490196078433,6.211184210526317,1.8000000000000007]
        ];
        $this->assertEquals($expectedResult['price_units'], $priceUnitResult->getPriceUnits());
        $this->assertEquals($expectedResult['price_unit_construcctions'], $priceUnitConstructionResult->getPriceUnits());
    }

    public function test_getSumPriceUnit_not_used()
    {
        // Arrange
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 10.4, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 200, 'ground_surface' => 0, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 250, 'ground_surface' => 30.4, 'construction_surface' => 354.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 350, 'ground_surface' => 0, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);

        // Act
        list($priceUnitResult, $priceUnitConstructionResult) = $this->groundRegistryService->getPriceUnitEvals($groundRegistries);

        // Assert
        $expectedResult = [
            'price_units' => [4.165384615384615, 0.5736842105263165],
            'price_unit_construcctions' => [9.40576923076923, 4.0111842105263165]
        ];
        $this->assertEquals($expectedResult['price_units'], $priceUnitResult->getPriceUnits());
        $this->assertEquals($expectedResult['price_unit_construcctions'], $priceUnitConstructionResult->getPriceUnits());
    }

    public function test_getSumPriceUnit_empty()
    {
        // Arrange
        $groundRegistries = collect([]);

        // Act
        list($priceUnitResult, $priceUnitConstructionResult) = $this->groundRegistryService->getPriceUnitEvals($groundRegistries);

        // Assert
        $this->assertEmpty($priceUnitResult->getPriceUnits());
        $this->assertEmpty($priceUnitConstructionResult->getPriceUnits());

    }

    public function test_getPriceUnitResult_min_price_unit()
    {
        // Arrange
        $type = 'min';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 10.4, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 200, 'ground_surface' => 20.4, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 250, 'ground_surface' => 30.4, 'construction_surface' => 354.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 350, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'min',
            'price_unit' =>  0.5736842105263165,
            'price_unit_construction' => 4.0111842105263165,
            'elements' => 4
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_max_price_unit()
    {
        // Arrange
        $type = 'max';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 10.4, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 200, 'ground_surface' => 20.4, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 350, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);
        $groundRegistryService = $this->app->make(GroundRegistryService::class);

        // Act
        $result = $groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'max',
            'price_unit' => 8.34392156862745,
            'price_unit_construction' => 11.015490196078431,
            'elements' => 3
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_avg_price_unit()
    {
        // Arrange
        $type = 'avg';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 0, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 500, 'ground_surface' => 20.4, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 355, 'ground_surface' => 30.4, 'construction_surface' => 354.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 350, 'ground_surface' => 0, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'avg',
            'price_unit' => 13.538717750257998,
            'price_unit_construction' => 7.513337203302374,
            'elements' => 2
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_avg_price_unit_not_used()
    {
        // Arrange
        $type = 'avg';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 0, 'construction_surface' => 154.5, 'subsidy' => 5.45]),

        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'avg',
            'price_unit' => 0,
            'price_unit_construction' => 0,
            'elements' => 0
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_type_not_valid()
    {
        // Arrange
        $type = 'invalid';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 100, 'ground_surface' => 12.5, 'construction_surface' => 154.5, 'subsidy' => 5.45]),
        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'invalid',
            'price_unit' => 0,
            'price_unit_construction' => 0,
            'elements' => 1
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_empty()
    {
        // Arrange
        $type = 'min';
        $groundRegistries = collect([]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'min',
            'price_unit' => 0,
            'price_unit_construction' => 0,
            'elements' => 0
        ];
        $this->assertEquals($expectedResult, $result);
    }
}
