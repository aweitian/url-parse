<?php

/**
 * @Author: awei.tian
 * @Date: 2016年7月14日
 * @Desc: 把URL的PATH部分按 / 分割成数组
 */
namespace Tian\UrlParse;

class Arr {
	public $raw;
	private $arr;
	/**
	 * 完整的URL 或者以 // || http(s):// 开头
	 *
	 * @param string $url        	
	 */
	public function __construct($url = "") {
		$this->raw = $url;
		if ($url == "") {
			$this->arr = array ();
		} else {
			if (preg_match ( '/^(https?:)?\/\//', $url )) {
				$this->arr = explode ( "/", trim ( parse_url ( $url, PHP_URL_PATH ), "/" ) );
			} else {
				$this->arr = explode ( "/", trim ( $url, "/" ) );
			}
		}
	}
	
	/**
	 *
	 * @param string $url        	
	 * @return \Tian\UrlParse\Arr
	 */
	public static function getInstance($url) {
		return new self ( $url );
	}
	
	/**
	 * 返回长度
	 *
	 * @return number
	 */
	public function getLength() {
		return count ( $this->arr );
	}
	/**
	 * 长度不在数组长度之内，在后面添加
	 *
	 * @param int $index        	
	 * @param string $pathname        	
	 */
	public function set($index, $pathname) {
		if ($index < $this->getLength ()) {
			$this->arr [$index] = $pathname;
		} else {
			$this->arr [] = $pathname;
		}
	}
	/**
	 * 不存在返回NULL
	 *
	 * @param int $index        	
	 * @return NULL | pathname
	 */
	public function get($index) {
		if ($index < $this->getLength ()) {
			return $this->arr [$index];
		} else {
			return null;
		}
	}
	/**
	 * 返回URL PATH部分
	 *
	 * @return string
	 */
	public function __toString() {
		$url = join ( "/", $this->arr );
		return $url == "" ? "/" : "/" . $url;
	}
	/**
	 * 别名__toString()
	 *
	 * @see \tian\urlPath\arr\__toString()
	 * @return string
	 */
	public function toString() {
		return $this->__toString ();
	}
}