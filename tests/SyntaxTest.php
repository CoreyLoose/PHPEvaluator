<?php
require dirname(__FILE__) . '/TestUtils.php';

class SyntaxTest extends PHPUnit_Framework_TestCase
{
    public function testFunction()
    {
        $eval = TestUtils::getEvalInstance();
        $this->assertEquals($eval->calculate('AVG(5,3,1)'), 3);
        $this->assertEquals($eval->calculate('AVG(5,3,1) + AVG(5,3,1)'), 6);
        $this->assertEquals($eval->calculate('(AVG(2,2) + AVG(2,2)) / 2'), 2);
        $this->assertEquals($eval->calculate('(AVG(3,3) + AVG(7,7)) / AVG(2,2)'), 5);
    }

    public function testConstants() {
        $eval = TestUtils::getEvalInstance();
        $this->assertEquals($eval->calculate('test + 100'), 200);
        $this->assertEquals($eval->calculate('test + test'), 200);
        $this->assertEquals($eval->calculate('AVG(test, 50)'), 75);
    }
}