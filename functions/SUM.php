<?php
/**
 * PHPEvaluator (http://github.com/CoreyLoose/PHPEvaluator)
 *
 * Licensed under The Clear BSD License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Corey Losenegger (http://coreyloose.com)
 * @license Clear BSD (http://labs.metacarta.com/license-explanation.html)
 */
 
class SUM extends AbstractFunction
{
	public function execute( array $arguments )
	{
		$sum = 0;
		foreach( $arguments as $arg ){
			$sum += $arg;
		}
		return $sum;
	}
}