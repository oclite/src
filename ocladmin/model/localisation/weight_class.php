<?php
class ModelLocalisationWeightClass extends Model {
	// Добавление единицы веса
	public function addWeightClass($data) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		$vals = unserialize($query->row['value']);
		$max = 1;
		foreach($vals as $key => $val)
		{
			if($data['title'] == $val['title'] || $data['unit'] == $val['unit']) return;
			if($val['weight_class_id'] > $max) $max = $val['weight_class_id'];
		}		
		$vals[] = array('weight_class_id' => (++$max), 'title' => $data['title'],'unit' => $data['unit'],'value' => $data['value']);
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_weight_class'");
		$this->cache->delete('weight_class');
	}
	
	// Изменение единицы веса 
	public function editWeightClass($weight_class_id, $data) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as &$val){
			if($val['weight_class_id'] == $weight_class_id) {
				$val['title'] = $data['title'];
				$val['unit'] = $data['unit'];
				$val['value'] = $data['value'];
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_weight_class'");
				$this->cache->delete('weight_class');
				break;
			} 
		}			
	}
	
	// Удаление единицы веса
	public function deleteWeightClass($weight_class_id) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $key => $val){
			if($val['weight_class_id'] == $weight_class_id) {
				unset($vals[$key]);
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_weight_class'");
				$this->cache->delete('weight_class');
				break;
			} 
		}	
	}
	
	// Получение единиц веса по параметрам
	public function getWeightClasses($data = array()) {
		$weight_class_data = $this->cache->get('weight_class');
		
		if (!$weight_class_data) {
			$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");	
			$weight_class_data = unserialize($query->row['value']);
			
			$this->cache->set('weight_class', $weight_class_data);
		}
		
		if ($data) {
			/*
			if(isset($data['sort'])) {
				$order = (isset($data['order']) && ($data['order'] == 'DESC'))? 1 : -1;
				$sort_data = array('title','unit','value');
				if (in_array($data['sort'], $sort_data)) {
					$c = "return  {$order} * strnatcmp(\$a['{$data['sort']}'], \$b['{$data['sort']}']);";
					usort($weight_class_data, create_function('$a, $b',$c));
				}
			}
			*/
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) $data['start'] = 0;
				if ($data['limit'] < 1) $data['limit'] = 20;	
			}
	
			return $weight_class_data;			
		} else return $weight_class_data;
	}
	
	// Получить единицу веса по его id
	public function getWeightClass($weight_class_id) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $val) if($val['weight_class_id'] == $weight_class_id) return $val;
		return array();
	}

	// Получить единицу веса по его названию
	public function getWeightClassDescriptionByUnit($unit) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $val) if($val['unit'] == $unit) return $val;		
		return array();
	}
	
	// Получить список всех единиц веса		
	public function getTotalWeightClasses() {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_weight_class'");
		
		return count(unserialize($query->row['value']));
	}		
}