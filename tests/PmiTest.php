<?php
class PmiTest extends PHPUnit_Framework_TestCase {
	public function testPre() {
		$demo = new \Tian\UrlParse\Pmi ( 'http://www.baidu.com/p1/p2/m1/m2/c/a/i1/i2/?a=b', [ 
				'http_entry' => '/p1/p2',
				'moduleSkip' => 2 
		] );
		$this->assertEquals ( 'm1/m2', $demo->module );
		$this->assertEquals ( 'c/a/i1/i2', $demo->info );
		$demo->setInfo ( '/i/j' );
		
		$url = new \Tian\Url ( $demo->raw );
		$url->setPath ( $demo->toString () );
		$url->setQuery ( 'c', 'gg' );
		$url->setQuery ( 'page', 1 );
		$url->setQuery ( 'h', 1 );
		$this->assertEquals ( 'http://www.baidu.com/p1/p2/m1/m2/i/j?a=b&c=gg&page=1&h=1', $url->toString ( false ) );
	}
}

