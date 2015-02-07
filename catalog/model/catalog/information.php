<?php
class ModelCatalogInformation extends Model {
	public function getInformation($information_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "' AND status = '1'");
	
		return $query->row;
	}
	
	public function getInformations() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information WHERE status = '1' ORDER BY sort_order, LCASE(title) ASC");
		
		return $query->rows;
	}
	
	public function getInformationLayoutId($information_id) {
		$query = $this->db->query("SELECT `layout_id` FROM `" . DB_PREFIX . "information` WHERE `information_id` = '" . (int)$information_id . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}	
}
