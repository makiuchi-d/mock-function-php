<?php
require_once __DIR__.'/../MockFunction.php';

class T {
	public $a;
	public function __construct($a)
	{
		$this->a = $a;
	}
};

function user_function($p)
{
	return $p;
}


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-05-22 at 00:57:28.
 */
class MockFunctionTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}

	/**
	 */
	public function testSimpleReplacement()
	{
		$testfile = __DIR__.'/testfile';

		$original = file_get_contents($testfile);

		MockFunction::replace('file_get_contents', 'dummy string');

		$str = file_get_contents($testfile);
		$this->assertEquals('dummy string',$str);

		MockFunction::restore('file_get_contents');

		$str = file_get_contents($testfile);
		$this->assertEquals($original,$str);
	}

	/**
	 */
	public function testNestedReplacement()
	{
		$testfile = __DIR__.'/testfile';

		$original = file_get_contents($testfile);

		MockFunction::replace('file_get_contents','dummy string');

		$str = file_get_contents($testfile);
		$this->assertEquals('dummy string',$str);

		MockFunction::replace('file_get_contents','dummy 2');

		$str = file_get_contents($testfile);
		$this->assertEquals('dummy 2',$str);

		MockFunction::restore('file_get_contents');

		$str = file_get_contents($testfile);
		$this->assertEquals('dummy string',$str);

		MockFunction::restore('file_get_contents');

		$str = file_get_contents($testfile);
		$this->assertEquals($original,$str);
	}

	/**
	 */
	public function testReplaceWithEval()
	{
		$testfile = __DIR__.'/testfile';

		MockFunction::replace('file_get_contents','$args[0]',false);
		$str = file_get_contents($testfile);
		$this->assertEquals('$args[0]',$str);

		MockFunction::replace('file_get_contents','$args[0]',true);
		$str = file_get_contents($testfile);
		$this->assertEquals($testfile,$str);

		MockFunction::restore('file_get_contents');
		MockFunction::restore('file_get_contents');
	}

	/**
	 */
	public function testReturnInteger()
	{
		MockFunction::replace('intval',10);
		$ret = intval(5.5);
		$this->assertEquals(10,$ret);

		MockFunction::replace('intval',20,true);
		$ret = intval(5.5);
		$this->assertEquals(20,$ret);

		MockFunction::restore('intval');

		$ret = intval(5.5);
		$this->assertEquals(10,$ret);

		MockFunction::restore('intval');

		$ret = intval(5.5);
		$this->assertEquals(5,$ret);
	}

	/**
	 */
	public function testReplaceUserFunc()
	{
		MockFunction::replace('user_function','dummy_param=$args[0]',true);
		$ret = user_function(5);
		$this->assertEquals('dummy_param=5',$ret);
		MockFunction::restore('user_function');

		$ret = user_function(10);
		$this->assertEquals(10,$ret);
	}

	/**
	 */
	public function testReturnInstance()
	{
		$t1 = new T(1);
		$t2 = new T(2);

		MockFunction::replace('user_function',$t1);
		$ret = user_function($t2);
		$this->assertEquals($t1,$ret);
		MockFunction::restore('user_function');

		$ret = user_function($t2);
		$this->assertEquals($t2,$ret);
	}


	/**
	 */
	public function testReplaceFunctions()
	{
		MockFunction::replace('file_get_contents','dummy string');
		MockFunction::replace('intval',100);

		$str = file_get_contents('php://input');
		$int = intval(1.1);

		$this->assertEquals('dummy string',$str);
		$this->assertEquals(100,$int);

		MockFunction::restore('file_get_contents');
		MockFunction::restore('intval');
	}

}
