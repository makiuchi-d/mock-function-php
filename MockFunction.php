<?php

/**
 */
class MockFunction {

	protected static $counter = array();
	protected static $retvals = array();
	protected static $evals = array();

	public static function invoke($funcname,$args)
	{
		$counter = self::$counter[$funcname];
		$retval = self::$retvals[$funcname][$counter];
		if(is_string($retval) && self::$evals[$funcname][$counter]){
			$retval = eval("return \"$retval\";");
		}
		return $retval;
	}

	/**
	 */
	public static function replace($funcname,$retval,$eval=false)
	{
		if(!isset(self::$counter[$funcname])){
			self::$counter[$funcname] = 0;
			runkit_function_rename($funcname,"__original__{$funcname}");
			runkit_function_add(
				$funcname,'',
				"return MockFunction::invoke('$funcname',func_get_args());");
		}
		self::$counter[$funcname] += 1;
		self::$retvals[$funcname][self::$counter[$funcname]] = $retval;
		self::$evals[$funcname][self::$counter[$funcname]] = $eval;
	}

	/**
	 */
	public static function restore($funcname)
	{
		if(!isset(self::$counter[$funcname])){
			return;
		}
		$count = self::$counter[$funcname];
		self::$counter[$funcname] -= 1;
		unset(self::$retvals[$count]);
		unset(self::$evals[$count]);
		if(self::$counter[$funcname]==0){
			unset(self::$counter[$funcname]);
			unset(self::$retvals[$funcname]);
			unset(self::$evals[$funcname]);
			runkit_function_remove($funcname);
			runkit_function_rename("__original__{$funcname}",$funcname);
		}
	}
}

