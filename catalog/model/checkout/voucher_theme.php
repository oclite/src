<?php 
class ModelCheckoutVoucherTheme extends Model {
	public function getVoucherTheme($voucher_theme_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");
		
		return $query->row;
	}
		
	public function getVoucherThemes($data = array()) {
      	if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "voucher_theme vt ORDER BY name";	
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$voucher_theme_data = $this->cache->get('voucher_theme');
		
			if (!$voucher_theme_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme ORDER BY name");
	
				$voucher_theme_data = $query->rows;
			
				$this->cache->set('voucher_theme', $voucher_theme_data);
			}	
	
			return $voucher_theme_data;				
		}
	}
}
