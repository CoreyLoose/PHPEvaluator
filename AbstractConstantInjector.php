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
 
abstract class AbstractConstantInjector extends AbstractFunction 
{
	private $_params = array();
	
	public function setParams( $params )
	{
		$this->_params = $params;
	}
	
	public function setParam( $key, $value )
	{
		$this->_params[$key] = $value;
	}
	
	public function getParam( $key )
	{
		if( !isset($this->_params[$key]) ) return null;
		return $this->_params[$key];
	}
}