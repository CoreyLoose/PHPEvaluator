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
 
abstract class AbstractFunction
{
	private $_evaluator;
	private $_possibleFlags = array();
	
	abstract function execute( array $arguments );
	
	public function setEvaluator( PhpEvaluator &$evaluator )
	{
		$this->_evaluator =& $evaluator;
	}
	
	protected function _getDefaultConstantResolver()
	{
		return $this->_evaluator->getConstantResolver();
	}
	
	protected function _setPossibleFlags( array $flags )
	{
		$this->_possibleFlags = $flags;
	}
	
	protected function _parseFlags( $arguments )
	{
		$numArgs = count($arguments);
		if( $numArgs == 0 ) return;
		
		$flags = array();
		foreach( $this->_possibleFlags as $flag ){
			if( strpos($flag, $arguments[$numArgs-1]) !== false ) {
				$flags[$flag] = true;
			}
		}
		
		return $flags;
	}
}