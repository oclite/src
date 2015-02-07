<?php
class ModelAccountCustomField extends Model {
	public function getCustomField($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` WHERE `custom_field_id`='" . (int)$custom_field_id . "'");
		
		return $query->row;	
	}
	
	public function getCustomFields() {
		$custom_field_data = array();
		
		$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` ORDER BY sort_order ASC");
		
		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = array();
			
			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` WHERE `custom_field_id`='" . (int)$custom_field['custom_field_id'] . "' ORDER BY `sort_order` ASC");
				
				foreach ($custom_field_value_query->rows as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}
						
			$custom_field_data[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}
		
		return $custom_field_data;
	}
	
	public function getCustomFieldsByCustomerGroupId($customer_group_id) {
		$custom_field_data = array();
		
		$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_customer_group` cfcg ON (cf.custom_field_id = cfcg.custom_field_id) WHERE cfcg.customer_group_id = '" . (int)$customer_group_id . "' ORDER BY cf.sort_order ASC");
		
		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = array();
			
			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value WHERE custom_field_id = '" . (int)$custom_field['custom_field_id'] . "'  ORDER BY sort_order ASC");
				
				foreach ($custom_field_value_query->rows as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}
						
			$custom_field_data[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'location'           => $custom_field['location'],
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true,
				'sort_order'         => $custom_field['sort_order']
			);
		}
		
		return $custom_field_data;
	}
	
	public function getCustomFieldValue($custom_field_value_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` WHERE `custom_field_id`='" . (int)$custom_field['custom_field_id'] . "' ORDER BY `sort_order` ASC");
		
		return $query->row;
	}		
}
