<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);
			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}
			foreach ($parts as $part) {
				$spart = substr($part,0,5);
				if($spart === 'path-'){ // path- = путь
					$part = substr($part,5);
					$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "category` WHERE `seourl` = '" . $this->db->escape($part) . "'");
					if ($query->num_rows) {
						if (!isset($this->request->get['path'])) $this->request->get['path'] = $query->row['category_id'];
						else $this->request->get['path'] .= '_' . $query->row['category_id'];
						continue;
					}
				}elseif($spart === 'item-'){ // item- = товар
					$part = substr($part,5);
					$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product` WHERE `seourl` = '" . $this->db->escape($part) . "'");
					if ($query->num_rows) {
						$this->request->get['product_id'] = $query->row['product_id'];
						continue;
					}
				}elseif($spart === 'info-'){ // info- = информационные страницы
					$part = substr($part,5);
					$query = $this->db->query("SELECT `information_id` FROM `" . DB_PREFIX . "information` WHERE `seourl` = '" . $this->db->escape($part) . "'");
					if ($query->num_rows) {
						$this->request->get['information_id'] = $query->row['information_id'];
						continue;
					}
				}elseif($spart === 'news-'){ // news- = новости
					$part = substr($part,5);
					$query = $this->db->query("SELECT `news_id` FROM `" . DB_PREFIX . "news` WHERE `seourl` = '" . $this->db->escape($part) . "'");
					if ($query->num_rows) {
						$this->request->get['news_id'] = $query->row['news_id'];
						continue;
					}
				}elseif($spart === 'manf-'){ // manf- = производители
					$part = substr($part,5);
					$query = $this->db->query("SELECT `manufacturer_id` FROM `" . DB_PREFIX . "manufacturer` WHERE `seourl` = '" . $this->db->escape($part) . "'");
					if ($query->num_rows) {
						$this->request->get['manufacturer_id'] = $query->row['manufacturer_id'];
						continue;
					}
				}elseif($spart === 'page-'){ // page- = страницы
					$this->request->get['page'] = intval(substr($part,5));
					continue;
				}

				$this->request->get['route'] = 'error/not_found';
				break;
			}
			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
				} elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				}elseif (isset($this->request->get['news_id'])) {
					$this->request->get['route'] = 'information/news';
				}
			}
			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}
	public function rewrite($link) {
		return $link;
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$url = '';
		$data = array();
		parse_str($url_info['query'], $data);
		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if ($data['route'] == 'information/information' && $key == 'information_id'){
					$query = $this->db->query("SELECT `seourl` FROM `" . DB_PREFIX . "information` WHERE `information_id` = '" . (int)$value . "'");
					if ($query->num_rows) {
						$url .= '/info-' . $query->row['seourl'];
						unset($data[$key]);
					}
				}
				elseif ($data['route'] == 'information/news'){
					if((int)$value < 1) { $url .= '/news-main'; unset($data[$key]); }
					else {
						$query = $this->db->query("SELECT `seourl` FROM `" . DB_PREFIX . "news` WHERE `news_id` = '" . (int)$value . "'");
						if ($query->num_rows) {
							$url .= '/news-' . $query->row['seourl'];
							unset($data[$key]);
						}
					}
				}
				elseif ($data['route'] == 'product/product' && $key == 'product_id'){
					$query = $this->db->query("SELECT `seourl` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$value . "'");
					if ($query->num_rows) {
						$url .= '/item-' . $query->row['seourl'];
						unset($data[$key]);
					}
				}
				elseif (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') {
					$query = $this->db->query("SELECT `seourl` FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$value . "'");
					if ($query->num_rows) {
						$url .= '/manf-' . $query->row['seourl'];
						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$query = $this->db->query("SELECT `seourl` FROM " . DB_PREFIX . "category WHERE `category_id` = '" . (int)$category . "'");
						if (isset($query->row['seourl'])) $url .= '/path-' . $query->row['seourl'];
						else {
							$url = '';
							break;
						}
					}
					unset($data[$key]);
				} 
				elseif ($key == 'route' && $value == 'common/home') $url = '/';
				else{
					$query = $this->db->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $this->db->escape($key . '=' . $value) . "'");
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						unset($data[$key]);
					}
				}
			}
		}
		if ($url) {
			unset($data['route']);
			$query = '';
			//file_put_contents('xxx.txt',file_get_contents('xxx.txt')."\n".var_export($data,true));
			if ($data) {
				foreach ($data as $key => $value) {
					if($key === 'page') {
						$url .= '/page-'.$value;
						continue;
					}
					$query .= '&' . rawurlencode($key) . '=' . rawurlencode($value);
				}
				if ($query) $query = '/?' . trim($query, '&');
			}
			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else return $link;
	}
}