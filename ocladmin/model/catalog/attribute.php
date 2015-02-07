<?php 
class ModelCatalogAttribute extends Model {
	// Добавление атрибута
	public function addAttribute($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET `attribute_group_id` = '" . (int)$data['attribute_group_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");	
	}

	// Редактирование атрибута
	public function editAttribute($attribute_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "attribute` SET `attribute_group_id` = '" . (int)$data['attribute_group_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `attribute_id` = '" . (int)$attribute_id . "'");
	}
	
	// Удаление атрибута
	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "'");
	}
	
	// Получить атрибут по его id-у	
	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "'");	
		return $query->row;
	}
	
	// Получение атрибутов	
	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT ag.name FROM " . DB_PREFIX . "attribute_group ag WHERE ag.attribute_group_id = a.attribute_group_id) AS attribute_group FROM " . DB_PREFIX . "attribute a";
    $wh = ' WHERE ';
		if (!empty($data['filter_name'])) {
			$sql .= $wh . "a.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
      $wh = ' AND ';
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= $wh . "a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
								
		$sort_data = array('a.name','attribute_group','a.sort_order');	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) $sql .= " ORDER BY " . $data['sort'];	
		else $sql .= " ORDER BY attribute_group, a.name";
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) $sql .= " DESC";
		else $sql .= " ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;				
			if ($data['limit'] < 1) $data['limit'] = 20;	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
		
	public function getAttributesByAttributeGroupId($data = array()) {
		$sql = "SELECT *, (SELECT ag.name FROM " . DB_PREFIX . "attribute_group ag WHERE ag.attribute_group_id = a.attribute_group_id) AS attribute_group FROM " . DB_PREFIX . "attribute a";
        $wh = ' WHERE ';
		if (!empty($data['filter_name'])) {
			$sql .= $wh . "a.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
            $wh = ' AND ';
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= $wh . "a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
								
		$sort_data = array(
			'a.name',
			'attribute_group',
			'a.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) $sql .= " ORDER BY " . $data['sort'];	
		else $sql .= " ORDER BY a.name";
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) $sql .= " DESC";
		else $sql .= " ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalAttributes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute");
		
		return $query->row['total'];
	}	
	
	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		
		return $query->row['total'];
	}		
}