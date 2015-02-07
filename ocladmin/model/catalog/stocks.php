<?php
class ModelCatalogStocks extends Model {
	public function addStocks($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "stocks` SET `name` = '" . $this->db->escape($data['name']) . "',".(isset($data['description'])? " `description` = '" . $this->db->escape($data['description']) . "'," : "")." `term` = '" . (int)$data['term'] . "'");
		
	}

	public function editStocks($stock_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "stocks` SET `name` = '" . $this->db->escape($data['name']) . "',".(isset($data['description'])? " `description` = '" . $this->db->escape($data['description']) . "'," : "")." `term` = '" . (int)$data['term'] . "' WHERE `stock_id` = '" . (int)$stock_id . "'");

		$this->cache->delete('stocks');
	}

	public function deleteStock($stock_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "stocks` WHERE `stock_id` = '" . (int)$stock_id . "'");
		$this->cache->delete('stocks');
	}

	public function getStock($stock_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stocks` WHERE `stock_id` = '" . (int)$stock_id . "'");

		return $query->row;
	}

	public function getStocks($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "stocks`";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE `name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'term'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else $sql .= " ORDER BY `name`";

		if (isset($data['term']) && ($data['term'] == 'DESC')) {
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

	public function getTotalStocks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "stocks`");

		return $query->row['total'];
	}
}