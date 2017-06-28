<?php

/**
 * @author awei.tian
 * date: 2013-9-16
 * 说明:
 * 	本类主要是按PMCAI规则解析 URL的PATH部分
 * 	P为HTTP入口目录
 * 	m为模块存放目录
 * 	c为控制器名称
 * 	a为动作名称
 * 	i为动作后剩余部分,称为INFO
 */
namespace Tian\UrlParse;

class Pmcai {
	public $raw;
	private $http_entry = "";
	private $http_entry_len = 0;
	private $mask = "ca";
	private $path;
	public $prefix = "";
	public $module = array ();
	public $info = array ();
	public $control = "";
	public $action = "";
	/**
	 * 去掉HTTP_ENTRY的URL PATH部分
	 *
	 * @var string
	 */
	public $realpath;
	
	/**
	 *
	 * @param string $url-完整路径
	 *        	['http_entry' => '', 'mask' => 'ca'] /^m*(ca|c)?$/
	 * @param array $conf$http_entry-入口路径        	
	 */
	public function __construct($url, array $conf = []) {
		if ($url) {
			$this->raw = $url;
			$http_entry = "";
			$http_entry = "";
			if (isset ( $conf ['http_entry'] )) {
				$this->prefix = $conf ['http_entry'];
				$this->http_entry = $conf ['http_entry'];
				$this->http_entry_len = strlen ( $conf ['http_entry'] );
			}
			if (isset ( $conf ['mask'] )) {
				$this->mask = $conf ['mask'];
			}
			$this->setPath ( parse_url ( $url, PHP_URL_PATH ) );
			$this->parse ();
		}
	}
	/**
	 * 设置PATHINFO，然后调用PARSE
	 *
	 * @param string $path        	
	 * @return \Tian\UrlParse\Pmcai
	 */
	public function setPath($path, $http_entry = null, $mask = null) {
		$this->path = $path;
		if (! is_null ( $http_entry )) {
			$this->prefix = $http_entry;
			$this->http_entry = $http_entry;
			$this->http_entry_len = strlen ( $this->http_entry );
		}
		if (! is_null ( $mask )) {
			$this->mask = $mask;
		}
		if ($this->http_entry_len && substr ( $this->path, 0, $this->http_entry_len ) == $this->http_entry) {
			$this->realpath = substr ( $this->path, $this->http_entry_len );
		} else {
			$this->realpath = $path;
		}
		return $this;
	}
	/**
	 *
	 * @param string $url        	
	 * @param array $conf        	
	 * @return Tian\UrlParse\Pmcai
	 */
	public static function getInstance($url, array $conf = array()) {
		return new self ( $url, $conf );
	}
	public function setMask($mask) {
		$this->mask = $mask;
	}
	public function parse() {
		if (! self::isValidMask ( $this->mask ))
			return;
		if ($this->path == "")
			return;
		$this->module = [ ];
		$this->info = [ ];
		$mca_path = $this->realpath;
		$mca_arr = explode ( "/", trim ( $mca_path, "/" ) );
		$x = 0;
		$pmcaii_mask_arr = str_split ( $this->mask );
		
		while ( $x < count ( $mca_arr ) ) {
			if ($x >= count ( $pmcaii_mask_arr ))
				$z = "i";
			else
				$z = $pmcaii_mask_arr [$x];
			$v = $mca_arr [$x];
			switch ($z) {
				case "m" :
					$this->module [] = $v;
					break;
				case "c" :
					$this->control = $v;
					break;
				case "a" :
					$this->action = $v;
					break;
				case "i" :
					$this->info [] = $v;
					break;
			}
			$x ++;
		}
	}
	/**
	 *
	 * @param string $v        	
	 * @return \Tian\UrlParse\Pmcai
	 */
	public function setControl($v) {
		$this->control = $v;
		return $this;
	}
	/**
	 *
	 * @param string $v        	
	 * @return \Tian\UrlParse\Pmcai
	 */
	public function setAction($v) {
		$this->action = $v;
		return $this;
	}
	public function __toString() {
		$ret = '';
		if ($this->prefix) {
			$ret .= '/' . trim ( $this->prefix, '/' );
		}
		if (! empty ( $this->module )) {
			$ret .= '/' . implode ( '/', $this->module );
		}
		if (! $this->control) {
			$ret .= '/' . $this->control;
		}
		if (! $this->action) {
			$ret .= '/' . $this->action;
		}
		if (! empty ( $this->info )) {
			$ret .= '/' . implode ( '/', $this->info );
		}
		return $ret;
	}
	/**
	 * 以字符串形式返回PATH部分
	 *
	 * @return string
	 */
	public function toString() {
		return $this->__toString ();
	}
	public static function isValidMask($mask) {
		return preg_match ( "/^m*(ca|c)?$/", $mask );
	}
}
