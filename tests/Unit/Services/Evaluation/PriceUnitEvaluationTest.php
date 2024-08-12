<?php

use App\Services\Evaluation\PriceUnitEvaluation;
use Tests\TestCase;

class PriceUnitEvaluationTest extends TestCase
{
    public function test_addPriceUnit()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();

        // Act
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Assert
        $expectedResult = [4.165384615384615, 5.3539215686274515, 2.7736842105263166];
        $this->assertEquals($expectedResult, $priceUnitEvaluation->getPriceUnits());
    }

    public function test_getCount()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Act
        $count = $priceUnitEvaluation->getCount();

        // Assert
        $this->assertEquals(3, $count);
    }

    public function test_evaluate_min()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);


        // Act
        $result = $priceUnitEvaluation->evaluate('min');

        // Assert
        $this->assertEquals(2.7736842105263166, $result);
    }

    public function test_evaluate_max()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Act
        $result = $priceUnitEvaluation->evaluate('max');

        // Assert
        $this->assertEquals(5.3539215686274515, $result);
    }

    public function test_evaluate_avg()
    {

        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Act
        $result = $priceUnitEvaluation->evaluate('avg');

        // Assert
        $this->assertEquals(4.097663464846128, $result);
    }

    public function test_evaluate_invalid_type()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Act
        $result = $priceUnitEvaluation->evaluate('invalid');

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_getPriceUnits()
    {
        // Arrange
        $priceUnitEvaluation = new PriceUnitEvaluation();
        $priceUnitEvaluation->addPriceUnit(4.165384615384615);
        $priceUnitEvaluation->addPriceUnit(5.3539215686274515);
        $priceUnitEvaluation->addPriceUnit(2.7736842105263166);

        // Act
        $result = $priceUnitEvaluation->getPriceUnits();

        // Assert
        $this->assertEquals([4.165384615384615, 5.3539215686274515, 2.7736842105263166], $result);
    }
}
