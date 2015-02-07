<?php
class ModelCatalogFilter extends Model {
	public function addFilter($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET name = '" . $this->db->escape($data['filter_group_name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		$filter_group_id = $this->db->getLastId();
		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET name = '" . $this->db->escape($filter['name']) . "', filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
			}
		}
	}
	public function editFilter($filter_group_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET name = '" . $this->db->escape($data['filter_group_name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				if ($filter['filter_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_id = '" . (int)$filter['filter_id'] . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter['name']) . "', sort_order = '" . (int)$filter['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter['name']) . "', sort_order = '" . (int)$filter['sort_order'] . "'");
				}
			}
		}
	}
	public function deleteFilter($filter_group_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
	}
	public function getFilterGroup($filter_group_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		return $query->row;
	}
	public function getFilterGroups($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` ";
		$sort_data = array(
			'name',
			'sort_order'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
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
	}
	public function getFilterGroupDescriptions($filter_group_id) {
		$query = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "filter_group WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		return $query->row['name'];
	}
	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group fg WHERE f.filter_group_id = fg.filter_group_id) AS `group` FROM " . DB_PREFIX . "filter f WHERE f.filter_id = '" . (int)$filter_id . "'");
		return $query->row;
	}
	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group fg WHERE f.filter_group_id = fg.filter_group_id) AS `group` FROM " . DB_PREFIX . "filter f";
		if (!empty($data['filter_name'])) {
			$sql .= " WHERE  f.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		$sql .= " ORDER BY f.sort_order ASC";
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
	}
	public function getFilterDescriptions($filter_group_id) {
		$filter_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		return $filter_query->rows;
	}
	public function getTotalFilterGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "filter_group`");
		return $query->row['total'];
	}
}