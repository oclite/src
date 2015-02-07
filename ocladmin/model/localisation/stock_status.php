<?php 
class ModelLocalisationStockStatus extends Model {
	
	// Добавление состояния склада
	public function addStockStatus($data) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");
		$vals = unserialize($query->row['value']);
		$max = 1;
		foreach($vals as $key => $val)
		{
			if($data['name'] == $val['name']) return;
			if($val['stock_status_id'] > $max) $max = $val['stock_status_id'];
		}		
		$vals[] = array('stock_status_id' => (++$max), 'name' => $data['name']);
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_stock_status'");
		$this->cache->delete('stock_status');
	}

	// Редактирование состояния склада
	public function editStockStatus($stock_status_id, $data) {
				$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");
		$vals = unserialize($query->row['value']);
		foreach($vals as &$val){
			if($val['stock_status_id'] == $stock_status_id) {
				$val['name'] = $data['name'];
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_stock_status'");
				$this->cache->delete('stock_status');
				break;
			} 
		}
	}
	
	// Удаление состояния склада
	public function deleteStockStatus($stock_status_id) {
			$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $key => $val){
			if($val['stock_status_id'] == $stock_status_id) {
				unset($vals[$key]);
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_stock_status'");
				$this->cache->delete('stock_status');
				break;
			} 
		}
	}
	
	// Получение состоянии склада	
	public function getStockStatus($stock_status_id) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $val) if($val['stock_status_id'] == $stock_status_id) return $val;
		return array();
	}
	
	// Получение списка состояния склада
	public function getStockStatuses($data = array()) {
		$stock_status_data = $this->cache->get('stock_status');

		if (!$stock_status_data) {
			$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");	
			$stock_status_data = unserialize($query->row['value']);
			$this->cache->set('stock_status', $stock_status_data);
		}
		
		if($stock_status_data == NULL) return array();
		return $stock_status_data;
	}
	
	// Получение колличества всех состоянии склада	
	public function getTotalStockStatuses() {
  	$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_stock_status'");
		
		return count(unserialize($query->row['value']));
	}	
}