<?php
require dirname(__FILE__) . '/../PhpEvaluator.php';
require dirname(__FILE__) . '/../functions/AVG.php';
require dirname(__FILE__) . '/../functions/ConstantInjector.php';

class TestUtils {
    public static function getEvalInstance() {
        $eval = new PhpEvaluator();
        $eval->registerFunction(new AVG());
        $eval->registerFunction(new ConstantInjector(array('test' => 100)));
        $eval->setConstantResolver('ConstantInjector');
        return $eval;
    }
}