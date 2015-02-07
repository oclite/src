<?php 
class ModelSaleVoucherTheme extends Model {
	// Добавление темы
	public function addVoucherTheme($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme` SET `name` = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($data['image']) . "'");
		$this->cache->delete('voucher_theme');
	}
	
	// Редактирование темы
	public function editVoucherTheme($voucher_theme_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher_theme` SET `name` = '" . $this->db->escape($data['name']) . "', `image` = '" . $this->db->escape($data['image']) . "' WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");		
		$this->cache->delete('voucher_theme');
	}
	
	// Удаление темы
	public function deleteVoucherTheme($voucher_theme_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_theme` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");	
		$this->cache->delete('voucher_theme');
	}
	
	// Получение темы по его id	
	public function getVoucherTheme($voucher_theme_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");
		return $query->row;
	}
	
	// Получение всех тем подарочного сертификата по сортировке и лимиту
	public function getVoucherThemes($data = array()) {
      	if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "voucher_theme` ORDER BY `name`";	
			
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
		} else {
			$voucher_theme_data = $this->cache->get('voucher_theme');
		
			if (!$voucher_theme_data) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme` ORDER BY `name`");
				$voucher_theme_data = $query->rows;		
				$this->cache->set('voucher_theme', $voucher_theme_data);
			}	
	
			return $voucher_theme_data;				
		}
	}
	
	// Получить кол-во всех тем
	public function getTotalVoucherThemes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "voucher_theme`");		
		return $query->row['total'];
	}	
}