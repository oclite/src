<?php
class ModelTotalHandling extends Model {
	public function getTotal(&$total_data, &$total) {
		if (($this->cart->getSubTotal() < $this->config->get('handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->load->language('total/handling');
		 	
			$total_data[] = array( 
				'code'       => 'handling',
        		'title'      => $this->language->get('text_handling'),
        		'value'      => $this->config->get('handling_fee'),
				'sort_order' => $this->config->get('handling_sort_order')
			);
			
			$total += $this->config->get('handling_fee');
		}
	}
}
