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

require_once 'AbstractFunction.php';

class PhpEvaluator
{
	private $_vars = array();
	private $_functions = array();
	private $_constantResolver;

	public function registerFunction( AbstractFunction $function )
	{
		$this->_functions[get_class($function)] = $function;	
	}
	
	public function setConstantResolver( $functionName )
	{
		if( !isset($this->_functions[$functionName]) ) {
			throw new Exception(
				'Unknown function "'.$functionName.'". Perhaps you'.
				' forgot to register first'
			);
		}
		$this->_constantResolver = $this->_functions[$functionName];
	}
	
	public function calculate( $string )
	{
		while(true)
		{
			$deepestNesting = $this->_deepestNesting($string);
			if( $deepestNesting == 0 ) break;
			$string = $this->_calculateDeepest($string, $deepestNesting);
		}

		if( $this->_containsConstant($string) ){
			$string = $this->_resolveConstants($string);
		}
		
		return $this->_evalMath($string);
	}
	
	protected function _calculateDeepest( $string, $deepestNesting )
	{
		$result = '';
		$open = $close = 0;
		$inputLength = strlen($string);
		for( $i=0; $i<$inputLength; $i++ )
		{
			if( $string[$i] == '(' ) {
				$open++;
			} else if( $string[$i] == ')' ) {
				$close++;
			}
			
			//keep digging until we reach the deepest nesting
			if( $open - $close != $deepestNesting ) {
				$result .= $string[$i];
				continue;	
			}
			
			/* If there is a possible function call, identified
			 * by a letters only string placed directly infront of the
			 * opening (, we walk backwords to find it
			 */
			$functionName = '';
			$prevI = $i;
			while( preg_match('/[a-zA-Z]/', $string[--$i]) ){
				$functionName .= $string[$i];				
			}
			if( $functionName ){
				$result = substr($result, 0, $prevI-($prevI-$i)+1);
				$functionName = strrev($functionName);
			}
			$i = $prevI;
						
			//walk forwards until we find the closing )
			$stringToCalc = '';
			while( $string[++$i] != ')' ){
				$stringToCalc .= $string[$i];
			}
			$close++;
			
			//do the math
			if( $functionName )
			{
				$result .= $this->_callFunction($functionName, $stringToCalc);					
			} 
			else if( $this->_containsConstant($stringToCalc) )
			{
				$result .=
					$this->_evalMath(
						$this->_resolveConstants($stringToCalc)
					);	
			}
			else
			{
				$result .= $this->_evalMath($stringToCalc);
			}
		}
		
		return $result;
	}
	
	protected function _evalMath( $stringToEval )
	{
		$result = 0;
		$stringToEval = '$result = '.$stringToEval.';';
		ob_start();
		eval($stringToEval);
		ob_clean();
		return $result;
	}
	
	protected function _callFunction( $functionName, $argsAsString )
	{
		if( !isset($this->_functions[$functionName]) ) {
			throw new Exception('Unknown function '.$functionName);
		}
		
		$args = explode(',', $argsAsString);
		foreach( $args as &$arg ) {
			$arg = trim($arg);
			if( $this->_containsConstant($arg) ) {
				$arg = $this->_resolveConstants($arg);
			}
		}
		
		return $this->_functions[$functionName]->execute($args);
	}
	
	protected function _containsConstant( $string )
	{
		if( preg_match("/[a-zA-Z]/", $string) ){
			return true;
		}
		return false;
	}
	
	protected function _resolveConstants( $string )
	{
		if( !$this->_constantResolver ){
			throw new Exception('Constant detected in formula with no resolver set');
		}
		
		$result = '';
		
		$stringSplit =
			preg_split(
				"/([a-zA-Z]+)/",
				$string,
				-1,
				PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
			);

		foreach( $stringSplit as $stringPart )
		{
			if( preg_match("/[a-zA-Z]/", $stringPart) ){
				$stringPart = $this->_constantResolver->execute(array($stringPart));
			}
			$result .= $stringPart;
		}
		
		return $result;
	}
	
	protected function _deepestNesting( $string )
	{
		$deepest = $open = $close = 0;
		$inputLength = strlen($string);
		for( $i=0; $i<$inputLength; $i++ ) {
			if( $string[$i] == '(' ) {
				$open++;
				$deepest = $open - $close;
			} else if( $string[$i] == ')' ) {
				$close++;
			}
		}
		
		if( $open != $close ) {
			throw new Exception('Invalid formula - Parentheses do not match.');
		}
		
		return $deepest;
	}
}