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
 
class ConstantInjector extends AbstractConstantInjector
{
	private $_vars;
	
	/**
	 * @param array $vars associative array of constants to values
	 */
	public function __construct( $vars )
	{
		$this->_vars = $vars;
	}
	
	public function execute( array $arguments )
	{
		$numArguments = count($arguments);
		if( $numArguments == 0 || $numArguments > 1 ){
			throw new Exception('Invalid number of arugments for ConstantInjector');
		}
		
		if( !isset($this->_vars[$arguments[0]]) ) {
			throw new Exception('Unkown constant "'.$arguments[0].'"');
		}
		
		return $this->_vars[$arguments[0]];
	}
}