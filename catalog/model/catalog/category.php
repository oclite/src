<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "' AND status = '1'");
		return $query->row;
	}
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "' AND status = '1' ORDER BY sort_order, LCASE(name)");
		return $query->rows;
	}
	public function getCategoryFilters($category_id) {
		$implode = array();
		$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}
		$filter_group_data = array();
		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fg.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fg.name)");
			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();
				$filter_query = $this->db->query("SELECT DISTINCT filter_id, name FROM " . DB_PREFIX . "filter WHERE filter_id IN (" . implode(',', $implode) . ") AND filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' ORDER BY sort_order, LCASE(name)");
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']
					);
				}
				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);
				}
			}
		}
		return $filter_group_data;
	}
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT `layout_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category_id . "'");
		if ($query->num_rows) return $query->row['layout_id'];
		return 0;
	}
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "' AND status = '1'");
		return $query->row['total'];
	}
}