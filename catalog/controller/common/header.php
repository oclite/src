<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle() . ' | ' . $this->config->get('config_name');
		$server = ($this->request->server['HTTPS'])? HTTP_SERVER : HTTPS_SERVER;
		$parts = (isset($this->request->get['path']))? explode('_', (string)$this->request->get['path']) : array();
		$data['category_id'] = (isset($parts[0]))? $parts[0] : 0;
		$data['child_id'] =  (isset($parts[1]))? $parts[1] : 0;
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		$data['custom_header'] = html_entity_decode($this->config->get('config_custom_header'), ENT_QUOTES, 'UTF-8');
		$data['name'] = $this->config->get('config_name');
		if (is_file(DIR_CONTENT . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'content/' . $this->config->get('config_icon');
		} else $data['icon'] = '';
		if (is_file(DIR_CONTENT . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'content/' . $this->config->get('config_logo');
		} else $data['logo'] = '';
		$this->load->language('common/header');
		$data['text_home'] = $this->language->get('text_home');
		$data['text_news'] = $this->language->get('text_news');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$data['text_account'] = $this->language->get('text_account');
    $data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_pricelist'] = $this->language->get('text_pricelist');
		$data['home'] = $this->url->link('common/home');
		$data['news'] = $this->url->link('information/news');
		$data['pricelist'] = $this->url->link('product/price');
		$data['contact'] = $this->url->link('information/contact');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['telephone'] = $this->config->get('config_telephone');
		$this->load->model('catalog/information');
		$data['informations'] = array();
		foreach ($this->model_catalog_information->getInformations() as $result) {
			if (($result['positions'] & 2) == 2) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
    }
		$status = true;
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));
			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;
					break;
				}
			}
		}
		// Menu
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$data['categories'] = array();
		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);
					$children_data[] = array( //. ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						'category_id' => $child['category_id'],
						'name'  => $child['name'] ,
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		$data['currency'] = $this->load->controller('module/currency');
		$data['search'] = $this->load->controller('module/search');
		$data['cart'] = $this->load->controller('module/cart');
		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else $class = '';
			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}