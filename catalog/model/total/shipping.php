<?php
class ModelTotalShipping extends Model {
	public function getTotal(&$total_data, &$total) {
		if (isset($this->session->data['shipping_method'])) {
			$total_data[] = array( 
				'code'       => 'shipping',
        		'title'      => $this->session->data['shipping_method']['title'],
        		'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('shipping_sort_order')
			);
			
			$total += $this->session->data['shipping_method']['cost'];
		}			
	}
}
