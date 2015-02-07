<?php
class Url {
	private $host;
	private $rewrite = array();
	public function __construct($host, $ssl = '') {
		$this->host = $host;
		$this->ssl = $ssl;
	}
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
	public function link($route, $args = '', $secure = false) {
		//$url = ($secure)? $this->ssl : $this->host;
		$url = ($secure)? HTTPS_SERVER : HTTP_SERVER;
		if($route == 'common/home') return $url;
		$url .= 'index.php?route=' . $route;
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
		return $url;
	}
}
