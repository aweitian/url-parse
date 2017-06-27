<?php
class ArrTest extends PHPUnit_Framework_TestCase {
	public function testPre() {
		$demo = new \Tian\UrlParse\Arr( 'http://www.baidu.com/p1/p2/m1/m2/c/a/i1/i2/?a=b');
		$this->assertEquals ( 'p2', $demo->get(1) );
		$this->assertEquals ( 'a', $demo->get(5) );
		$demo->set(2,'mm1');

		$url = new \Tian\Url($demo->raw);
		$url->setPath($demo->toString());
		$url->setQuery('c', 'gg');
		$url->setQuery('page', 1);
		$url->setQuery('h', 1);
		$this->assertEquals('http://www.baidu.com/p1/p2/mm1/m2/c/a/i1/i2?a=b&c=gg&page=1&h=1', $url->toString(false));
	}
}

