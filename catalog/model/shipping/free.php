<?php
class ModelShippingFree extends Model {
	function getQuote($address) {
		$this->load->language('shipping/free');
		$zone_id = (int)$address['zone_id'];
		$config_zone_id = (int)$this->config->get('free_geo_zone_id');
	
		$status = ($zone_id == $config_zone_id)? true : false;

		if ($this->cart->getSubTotal() < $this->config->get('free_total')) {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['free'] = array(
        		'code'         => 'free.free',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => 0.00,
        		'tax_class_id' => 0,
        		'text'         => $this->currency->format(0.00)
      		);

      		$method_data = array(
        		'code'       => 'free',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
        		'comment'		 => $this->config->get('free_comment'),
        		'sort_order' => $this->config->get('free_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
}
