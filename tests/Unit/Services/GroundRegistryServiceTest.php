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
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 10006, 'ground_surface' => 10.4, 'construction_surface' => 600.445, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 8004, 'ground_surface' => 20.4, 'construction_surface' => 452.11, 'subsidy' => 4.45]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 9000, 'ground_surface' => 30.4, 'construction_surface' => 900.56, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 16650, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 9.45]),
        ]);

        // Act
        list($priceUnitResult, $priceUnitConstructionResult) = $this->groundRegistryService->getPriceUnitEvals($groundRegistries);

        // Assert
        $expectedResult = [
            'price_units' => [956.6653846153845, 387.9029411764706, 290.6026315789474, 402.67871287128713],
            'price_unit_construcctions' => [11.21430730541515, 13.2536561898653, 4.543781646975217, 27.183663366336635]
        ];
        $this->assertEquals($expectedResult['price_units'], $priceUnitResult->getPriceUnits());
        $this->assertEquals($expectedResult['price_unit_construcctions'], $priceUnitConstructionResult->getPriceUnits());
    }

    public function test_getSumPriceUnit_not_used()
    {
        // Arrange
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 8000, 'ground_surface' => 10.4, 'construction_surface' => 500.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 2000, 'ground_surface' => 0, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 6000, 'ground_surface' => 30.4, 'construction_surface' => 600.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 3500, 'ground_surface' => 0, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);

        // Act
        list($priceUnitResult, $priceUnitConstructionResult) = $this->groundRegistryService->getPriceUnitEvals($groundRegistries);

        // Assert
        $expectedResult = [
            'price_units' => [763.7807692307691, 189.71842105263158],
            'price_unit_construcctions' => [10.534015984015983, 2.341673605328893]
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
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 6000, 'ground_surface' => 10.4, 'construction_surface' => 500.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 5000, 'ground_surface' => 20.4, 'construction_surface' => 350.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 6000, 'ground_surface' => 30.4, 'construction_surface' => 354.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 3500, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'min',
            'price_unit' => 83.18366336633663,
            'price_unit_construction' => 4.250770077007701,
            'elements' => 4
        ];
        $this->assertEquals($expectedResult, $result);
    }

    public function test_getPriceUnitResult_max_price_unit()
    {
        // Arrange
        $type = 'max';
        $groundRegistries = collect([
            $this->makeMockGroundRegistry(['id' => 1, 'ground_value' => 1000, 'ground_surface' => 10.4, 'construction_surface' => 500.5, 'subsidy' => 5.45]),
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 2000, 'ground_surface' => 20.4, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 3500, 'ground_surface' => 40.4, 'construction_surface' => 454.5, 'subsidy' => 3.45]),
        ]);
        $groundRegistryService = $this->app->make(GroundRegistryService::class);

        // Act
        $result = $groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'max',
            'price_unit' => 96.57921568627452,
            'price_unit_construction' => 6.398546168958743,
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
            $this->makeMockGroundRegistry(['id' => 2, 'ground_value' => 5000, 'ground_surface' => 150.5, 'construction_surface' => 254.5, 'subsidy' => 1.46]),
            $this->makeMockGroundRegistry(['id' => 3, 'ground_value' => 3550, 'ground_surface' => 550.4, 'construction_surface' => 354.5, 'subsidy' => 7.65]),
            $this->makeMockGroundRegistry(['id' => 4, 'ground_value' => 3500, 'ground_surface' => 454, 'construction_surface' => 0, 'subsidy' => 3.45]),
        ]);

        // Act
        $result = $this->groundRegistryService->getPriceUnitResult($type, $groundRegistries);

        // Assert
        $expectedResult = [
            'type' => 'avg',
            'price_unit' => 15.281223006644517,
            'price_unit_construction' => 10.275234897376144,
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
