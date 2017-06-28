<?php

/**
 * @Author: awei.tian
 * @Date: 2016年7月26日
 * @Desc: 
 * 		把URL的M后面部分全部提取作为参数传递给模块的构造函数
 */
namespace Tian\UrlParse;

class Pmi {
	public $raw;
	private $prefix = "";
	private $http_entry = "";
	private $http_entry_len = 0;
	private $moduleSkip = 0;
	/**
	 * 去掉HTTP_ENTRY的URL PATH部分
	 *
	 * @var string
	 */
	public $realpath = "";
	public $module = "";
	public $info = "";
	private $path;
	/**
	 *
	 * @param string $url-完整路径
	 *        	http_entry-入口路径,moduleSkip-模块目录长度
	 * @param array $conf        	
	 *
	 */
	public function __construct($url, array $conf = []) {
		if ($url) {
			$this->raw = $url;
			if (isset ( $conf ['http_entry'] )) {
				$this->prefix = $conf ['http_entry'];
				$this->http_entry = $conf ['http_entry'];
				$this->http_entry_len = strlen ( $conf ['http_entry'] );
			}
			if (isset ( $conf ['moduleSkip'] )) {
				$this->moduleSkip = $conf ['moduleSkip'];
			}
			$this->setPath ( parse_url ( $url, PHP_URL_PATH ) );
			$this->parse ();
		}
	}
	/**
	 *
	 * @param string $path        	
	 * @param string $http_entry        	
	 * @param int $moduleSkip        	
	 * @return \Tian\UrlParse\Pmi
	 */
	public function setPath($path, $http_entry = null, $moduleSkip = null) {
		$this->path = $path;
		if (! is_null ( $http_entry )) {
			$this->prefix = $http_entry;
			$this->http_entry = $http_entry;
			$this->http_entry_len = strlen ( $this->http_entry );
		}
		if (! is_null ( $moduleSkip )) {
			$this->moduleSkip = $moduleSkip;
		}
		if ($this->http_entry_len && substr ( $this->path, 0, $this->http_entry_len ) == $this->http_entry) {
			$this->realpath = substr ( $this->path, $this->http_entry_len );
			if ($this->realpath === false) {
				$this->realpath = "";
			}
		} else {
			$this->realpath = $this->path;
		}
		return $this;
	}
	public function parse() {
		$moduleSkip = $this->moduleSkip;
		if ($moduleSkip > 0) {
			$tmp = explode ( "/", trim ( $this->realpath, "/" ), $moduleSkip + 1 );
			if (count ( $tmp ) > $moduleSkip) {
				$this->info = array_pop ( $tmp );
				$this->module = join ( "/", $tmp );
			} else {
				$this->module = $this->realpath;
				$this->info = "";
			}
		} else {
			$this->module = "";
			$this->info = $this->realpath;
		}
	}
	/**
	 *
	 * @param string $url        	
	 * @param array $conf        	
	 * @return \Tian\UrlParse\Pmi
	 */
	public static function getInstance($url, array $conf = array()) {
		return new self ( $url, $conf );
	}
	/**
	 *
	 * @param string $v        	
	 * @return \Tian\UrlParse\Pmi
	 */
	public function setInfo($v) {
		$this->info = $v;
		return $this;
	}
	
	/**
	 * 返回URL PATH部分
	 *
	 * @return string
	 */
	public function toString() {
		return $this->__toString ();
	}
	public function __toString() {
		$ret = '';
		if ($this->prefix) {
			$ret .= '/' . trim ( $this->prefix, '/' );
		}
		if ($this->module) {
			$ret .= '/' . trim ( $this->module, '/' );
		}
		if ($this->info) {
			$ret .= '/' . trim ( $this->info, '/' );
		}
		return $ret;
	}
}