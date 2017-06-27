<?php
class PmcaiTest extends PHPUnit_Framework_TestCase {
	public function testPre() {
		$demo = new \Tian\UrlParse\Pmcai('http://www.baidu.com/p1/p2/m1/m2/c/a/i1/i2','/p1/p2','mmca');
		$this->assertArraySubset(['m1','m2'], $demo->module);
		$this->assertArraySubset(['i1','i2'], $demo->info);
		$this->assertEquals('/p1/p2', $demo->prefix);
		$this->assertEquals('c', $demo->control);
		$this->assertEquals('a', $demo->action);

		$demo->setPath('/m/c/a',null,'mca')->parse();
		$this->assertArraySubset(['m'], $demo->module);
		$this->assertEmpty($demo->info);
		$this->assertEquals('/p1/p2', $demo->prefix);
		$this->assertEquals('c', $demo->control);
		$this->assertEquals('a', $demo->action);
		
		
		$demo->setPath('/m/c/a/i1',null,'mca')->parse();
		$this->assertArraySubset(['m'], $demo->module);
		$this->assertArraySubset(['i1'], $demo->info);
		$this->assertEquals('/p1/p2', $demo->prefix);
		$this->assertEquals('c', $demo->control);
		$this->assertEquals('a', $demo->action);
		
		$demo = new \Tian\UrlParse\Pmcai('http://www.baidu.com/?c=g');
		$this->assertEmpty($demo->module);
		$this->assertEmpty($demo->info);
		$this->assertEmpty($demo->prefix);
		$this->assertEmpty($demo->control);
		$this->assertEmpty($demo->action);
		
		$demo->setPath('/a/b/c',null,'ca')->parse();
		$this->assertArraySubset(['c'], $demo->info);
		$this->assertEquals('a', $demo->control);
		$this->assertEquals('b', $demo->action);
		
		$url = new \Tian\Url($demo->raw);
		$url->setPath($demo->toString());
		$url->setQuery('c', 'gg');
		$url->setQuery('page', 1);
		$url->setQuery('h', 1);
// 		var_dump($url->toString(false));
		$this->assertEquals('http://www.baidu.com/c?c=gg&page=1&h=1', $url->toString(false));
	}
}

