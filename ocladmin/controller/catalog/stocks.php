<?php    
class ControllerCatalogStocks extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('catalog/stocks');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/stocks');
		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/stocks');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/stocks');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_stocks->addStocks($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/stocks');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/stocks');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_stocks->editStocks($this->request->get['stocks_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/stocks');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/stocks');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $stocks_id) {
				$this->model_catalog_stocks->deleteStocks($stocks_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$sort = (isset($this->request->get['sort']))? $this->request->get['sort'] : 'name';
		$order = (isset($this->request->get['order']))? $this->request->get['order'] : 'ASC';
		$page = (isset($this->request->get['page']))? $this->request->get['page'] : 1;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('catalog/stocks/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/stocks/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['stocks'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$stocks_total = $this->model_catalog_stocks->getTotalStocks();

		$results = $this->model_catalog_stocks->getStocks($filter_data);

		foreach ($results as $result) {
			$data['stocks'][] = array(
				'stock_id' 		=> $result['stock_id'],
				'name'  => $result['name'],
				'term'  => $result['term'],
				'edit'      	=> $this->url->link('catalog/stocks/update', 'token=' . $this->session->data['token'] . '&stock_id=' . $result['stock_id'] . $url, 'SSL')
			);
		}	

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_term'] = $this->language->get('column_term');
		$data['column_action'] = $this->language->get('column_action');		

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else $data['error_warning'] = '';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else $data['success'] = '';

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else $data['selected'] = array();

		$url = '';

		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_term'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . '&sort=term' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $stocks_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($stocks_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($stocks_total - $this->config->get('config_limit_admin'))) ? $stocks_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $stocks_total, ceil($stocks_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/stocks_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_term'] = $this->language->get('entry_term');

		$data['help_term'] = $this->language->get('help_term');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['error_warning'] = (isset($this->error['warning']))? $this->error['warning'] : '';

		$data['error_name'] = (isset($this->error['name']))? $this->error['name'] : '';

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['stock_id'])) {
			$data['action'] = $this->url->link('catalog/stocks/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/stocks/update', 'token=' . $this->session->data['token'] . '&stocks_id=' . $this->request->get['stock_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['stock_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$stocks_info = $this->model_catalog_stocks->getStock($this->request->get['stock_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($stocks_info)) {
			$data['name'] = $stocks_info['name'];
		} else $data['name'] = '';
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($stocks_info)) {
			$data['description'] = $stocks_info['description'];
		} else $data['description'] = '';


		if (isset($this->request->post['term'])) {
			$data['term'] = $this->request->post['term'];
		} elseif (!empty($stocks_info)) {
			$data['term'] = $stocks_info['term'];
		} else $data['term'] = '';

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('catalog/stocks_form.tpl', $data));
	}  

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/stocks')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/stocks')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $stocks_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByStocksId($stocks_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}	
		}

		return !$this->error;  
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/stocks');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_stocks->getStockss($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'stocks_id' => $result['stocks_id'], 
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}	
}