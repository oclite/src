<?php
class ControllerModuleCategory extends Controller {
	public function index($setting) {
		$this->load->language('module/category');
		$data['heading_title'] = $this->language->get('heading_title');
		$parts = (isset($this->request->get['path']))? explode('_', (string)$this->request->get['path']) : array();
		$data['category_id'] = (isset($parts[0]))? $parts[0] : 0;
		$data['child_id'] =  (isset($parts[1]))? $parts[1] : 0;
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$data['categories'] = array();
		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			$children_data = array();
			$children = $this->model_catalog_category->getCategories($category['category_id']);
			foreach ($children as $child) {
				$filter_data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);
				$htmladd = '';
				if($this->config->get('config_product_count')) {
					$t = $this->model_catalog_product->getTotalProductsIc($filter_data);
					if($t > 0) $htmladd = ' <small>(' . $t . ')</small>';
				}
				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'		=> $child['name'] . $htmladd,
					'href'		=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
				);
			}
			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);
			$htmladd = '';
			if($this->config->get('config_product_count')) {
				$t = $this->model_catalog_product->getTotalProductsIc($filter_data);
				if($t > 0) $htmladd = ' <small>(' . $t . ')</small>';
			}
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'		=> $category['name'] . $htmladd,
				'children'	=> $children_data,
				'href'		=> $this->url->link('product/category', 'path=' . $category['category_id'])
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
		$module = $this->config->get('category_module');
		foreach ($module as $m){
			if((int)$m['layout_id'] == $layout_id) {$data['position'] = $m['position']; break; }
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/category.tpl', $data);
		} else {
			return $this->load->view('default/template/module/category.tpl', $data);
		}
  	}
}