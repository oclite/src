<?php
class ControllerShippingFree extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('shipping/free');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('free', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['entry_descr'] = $this->language->get('entry_descr');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_comment'] = $this->language->get('help_comment');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
 		$data['error_warning'] = (isset($this->error['warning']))? $this->error['warning'] : '';
  		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);
   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
   		);
   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/free', 'token=' . $this->session->data['token'], 'SSL')
   		);
		$data['action'] = $this->url->link('shipping/free', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->post['free_descr'])) {
			$data['free_descr'] = $this->request->post['free_descr'];
		} else {
			$data['free_descr'] = $this->config->get('free_descr');
		}
		if (isset($this->request->post['free_total'])) {
			$data['free_total'] = $this->request->post['free_total'];
		} else {
			$data['free_total'] = $this->config->get('free_total');
		}
		if (isset($this->request->post['free_geo_zone_id'])) {
			$data['free_geo_zone_id'] = $this->request->post['free_geo_zone_id'];
		} else {
			$data['free_geo_zone_id'] = $this->config->get('free_geo_zone_id');
		}
		if (isset($this->request->post['free_comment'])) {
			$data['free_comment'] = $this->request->post['free_comment'];
		} else {
			$data['free_comment'] = $this->config->get('free_comment');
		}
		$zone_id = $this->config->get('config_zone_id');
		$this->load->model('localisation/zone');
		$data['geo_zones'] = $this->model_localisation_zone->getZone($zone_id);
		if (isset($this->request->post['free_status'])) {
			$data['free_status'] = $this->request->post['free_status'];
		} else {
			$data['free_status'] = $this->config->get('free_status');
		}
		if (isset($this->request->post['free_sort_order'])) {
			$data['free_sort_order'] = $this->request->post['free_sort_order'];
		} else {
			$data['free_sort_order'] = $this->config->get('free_sort_order');
		}
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('shipping/free.tpl', $data));
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}