<?php
class ModelCatalogOption extends Model {
	// Добавление опиции
	public function addOption($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET `name` = '" . $this->db->escape($data['name']) . "', `type` = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$option_id = $this->db->getLastId();

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET `option_id` = '" . (int)$option_id . "', `name` = '" . $this->db->escape($option_value['name']) . "', `image` = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
			}
		}
	}
	
	// Редактирование опции
	public function editOption($option_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `name` = '" . $this->db->escape($data['name']) . "',`type` = '" . $this->db->escape($data['type']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `option_id` = '" . (int)$option_id . "'");
				
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value` WHERE `option_id` = '" . (int)$option_id . "'");
		
		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_value_id` = '" . (int)$option_value['option_value_id'] . "', `option_id` = '" . (int)$option_id . "', `name` = '" . $this->db->escape($option_value['name']) . "', `image` = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$option_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_id` = '" . (int)$option_id . "', `name` = '" . $this->db->escape($option_value['name']) . "', `image` = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$option_value['sort_order'] . "'");
				}
			}
			
		}
	}
	
	// Удаление опции
	public function deleteOption($option_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option` WHERE option_id = '" . (int)$option_id . "'");	
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
	}
	
	// Получить опцию по id
	public function getOption($option_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE `option_id` = '" . (int)$option_id . "'");
		
		return $query->row;
	}
	
	// Получить опции
	public function getOptions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option`";
		
		if (isset($data['filter_name']) && $data['filter_name'] !== null) {
			$sql .= " WHERE `name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array('name','type','sort_order');	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else $sql .= " ORDER BY `name`";
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else $sql .= " ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;	
						
			if ($data['limit'] < 1) $data['limit'] = 20;	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` WHERE `option_value_id` = '" . (int)$option_value_id . "'");
		
		return $query->row;
	}
	
	public function getOptionValues($option_id) {
		$option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` WHERE `option_id` = '" . (int)$option_id . "' ORDER BY `sort_order`, `name`");
		
		return $option_value_query->rows;
	}
	
	
	public function getTotalOptions() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "option`"); 
		return $query->row['total'];
	}		
}