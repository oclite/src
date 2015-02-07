<?php
class ModelLocalisationLengthClass extends Model {
	// Добавление единицы веса
	public function addLengthClass($data) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		$vals = unserialize($query->row['value']);
		$max = 1;
		foreach($vals as $key => $val)
		{
			if($data['title'] == $val['title'] || $data['unit'] == $val['unit']) return;
			if($val['length_class_id'] > $max) $max = $val['length_class_id'];
		}		
		$vals[] = array('length_class_id' => (++$max), 'title' => $data['title'],'unit' => $data['unit'],'value' => $data['value']);
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_length_class'");
		$this->cache->delete('length_class');
	}
	
	// Изменение единицы веса 
	public function editLengthClass($length_class_id, $data) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as &$val){
			if($val['length_class_id'] == $length_class_id) {
				$val['title'] = $data['title'];
				$val['unit'] = $data['unit'];
				$val['value'] = $data['value'];
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_length_class'");
				$this->cache->delete('length_class');
				break;
			} 
		}			
	}
	
	// Удаление единицы веса
	public function deleteLengthClass($length_class_id) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $key => $val){
			if($val['length_class_id'] == $length_class_id) {
				unset($vals[$key]);
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='".$this->db->escape(serialize($vals))."' WHERE `key`='config_length_class'");
				$this->cache->delete('length_class');
				break;
			} 
		}	
	}
	
	// Получение единиц веса по параметрам
	public function getLengthClasses($data = array()) {
		$length_class_data = $this->cache->get('length_class');
		
		if (!$length_class_data) {
			$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");	
			$length_class_data = unserialize($query->row['value']);
			
			$this->cache->set('length_class', $length_class_data);
		}
		
		if ($data) {
			/*
			if(isset($data['sort'])) {
				$order = (isset($data['order']) && ($data['order'] == 'DESC'))? 1 : -1;
				$sort_data = array('title','unit','value');
				if (in_array($data['sort'], $sort_data)) {
					$c = "return  {$order} * strnatcmp(\$a['{$data['sort']}'], \$b['{$data['sort']}']);";
					usort($length_class_data, create_function('$a, $b',$c));
				}
			}
			*/
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) $data['start'] = 0;
				if ($data['limit'] < 1) $data['limit'] = 20;	
			}
	
			return $length_class_data;			
		} else return $length_class_data;
	}
	
	// Получить единицу веса по его id
	public function getLengthClass($length_class_id) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $val) if($val['length_class_id'] == $length_class_id) return $val;
		return array();
	}

	// Получить единицу веса по его названию
	public function getLengthClassDescriptionByUnit($unit) {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		$vals = unserialize($query->row['value']);
		foreach($vals as $val) if($val['unit'] == $unit) return $val;		
		return array();
	}
	
	// Получить список всех единиц веса		
	public function getTotalLengthClasses() {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key`='config_length_class'");
		
		return count(unserialize($query->row['value']));
	}		
}