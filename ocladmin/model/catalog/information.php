<?php
class ModelCatalogInformation extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', meta_title = '" . $this->db->escape($data['meta_title']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "',".(isset($data['information_layout'])? " `layout_id` = '" . (int)$data['information_layout'] . "'," : "")." positions = '" . (isset($data['positions']) ? (int)$data['positions'] : 0) . "', status = '" . (int)$data['status'] . "'");

		$information_id = $this->db->getLastId();

		if (isset($data['seourl'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['seourl']) . "'");
		}

		$this->cache->delete('information');
	}

	public function editInformation($information_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', meta_title = '" . $this->db->escape($data['meta_title']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "',".(isset($data['information_layout'])? " `layout_id` = '" . (int)$data['information_layout'] . "'," : "")." positions = '" . (isset($data['positions']) ? (int)$data['positions'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE information_id = '" . (int)$information_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id. "'");

		if ($data['seourl']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['seourl']) . "'");
		}

		$this->cache->delete('information');
	}

	public function deleteInformation($information_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");

		$this->cache->delete('information');
	}

	public function getInformation($information_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS seourl FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");

		return $query->row;
	}

	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information";

			$sort_data = array(
				'title',
				'sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
			}

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
			$information_data = $this->cache->get('information');

			if (!$information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information ORDER BY title");

				$information_data = $query->rows;

				$this->cache->set('information', $information_data);
			}

			return $information_data;
		}
	}

	public function getInformationDescriptions($information_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "information` WHERE `information_id` = '" . (int)$information_id . "'")->row;
	}

	public function getInformationLayout($information_id) {

		return $this->db->query("SELECT `layout_id` FROM `" . DB_PREFIX . "information` WHERE information_id = '" . (int)$information_id . "'")->row['layout_id'];
	}

	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "information`");

		return $query->row['total'];
	}

	public function getTotalInformationsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "information` WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
