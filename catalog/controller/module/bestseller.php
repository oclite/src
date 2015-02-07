<?php
class ControllerModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language('module/bestseller');
		$data['heading_title'] = $this->language->get('heading_title');
		$this->load->language('product/category');
		$data['text_in_pack'] = $this->language->get('text_in_pack');
		$data['text_unit_price'] = $this->language->get('text_unit_price');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['products'] = array();
		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $setting['image_width'], $setting['image_height']);
			}
			
			$price = (float)$result['price'];
			$special = (float)$result['special'];
			$qpbox = (int) $result['qpbox'];
			
			$unit_price = 0;
			
			if($qpbox > 1 && $price){
				if($special > 0) $unit_price = $this->currency->format($special / $qpbox);
				else $unit_price = $this->currency->format($price / $qpbox);
			}
			$price = $this->currency->format($price);
			$special = $this->currency->format($special);
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else $rating = false;
							
			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'   	  => $image,
				'name'    	  => addslashes($result['name']),
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'			=> $price,
				'special'		=> $special,
				'qpbox'			=> $qpbox,
				'unit_price'	=> $unit_price,
				'rating'		=> $rating,
				'href'			=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}
		$route = (isset($this->request->get['route']))? (string)$this->request->get['route'] : 'common/home';
		$layout_id = 0;
		if (substr($route, 0, 16) == 'product/category' && isset($this->request->get['path'])) {
			$path = explode('_', (string)$this->request->get['path']);
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}
		if (substr($route, 0, 15) == 'product/product' && isset($this->request->get['product_id'])) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}
		if (substr($route, 0, 23) == 'information/information' && isset($this->request->get['information_id'])) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}
		if (!$layout_id) $layout_id = $this->model_design_layout->getLayout($route);
		if (!$layout_id) $layout_id = $this->config->get('config_layout_id');
		$module = $this->config->get('bestseller_module');
		foreach ($module as $m){
			if((int)$m['layout_id'] == $layout_id) {$data['position'] = $m['position']; break; }
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/bestseller.tpl', $data);
		} else {
			return $this->load->view('default/template/module/bestseller.tpl', $data);
		}
	}
}
