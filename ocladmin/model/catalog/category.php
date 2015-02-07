<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `name` = '" . $this->db->escape($data['category_description']['name']) . "', `description` = '" . $this->db->escape($data['category_description']['description']) . "', `meta_title` = '" . $this->db->escape($data['category_description']['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['category_description']['meta_description']) . "', `seourl`= '" . $this->db->escape($data['keyword']) . "',`meta_keyword` = '" . $this->db->escape($data['category_description']['meta_keyword']) . "',".(isset($data['image'])? "`image` = '" . $this->db->escape($data['image']) . "'," : "")." `column` = '" . (int)$data['column'] . "',".(isset($data['category_layout'])? "`layout_id` ='".(int)$data['category_layout']."'," : "")." `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (int)$data['status'] . "', `date_modified` = NOW(), `date_added` = NOW()");
		$category_id = $this->db->getLastId();
		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
			$level++;
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		$this->cache->delete('category');
	}
	public function editCategory($category_id, $data) {
		$this->db->query('UPDATE `' . DB_PREFIX . 'category` SET `parent_id` = "' . (int)$data['parent_id'] . '", `top` = "' . (isset($data['top']) ? (int)$data['top'] : 0) . '", `name` = "' . $this->db->escape($data['category_description']['name']) . '", `description` = "' . $this->db->escape($data['category_description']['description']) . '", `meta_title` = "' . $this->db->escape($data['category_description']['meta_title']) . '", `meta_description` = "' . $this->db->escape($data['category_description']['meta_description']) . '", `seourl` = "' . $this->db->escape($data['keyword']) . '", `meta_keyword` = "' . $this->db->escape($data['category_description']['meta_keyword']) . '",'.(isset($data['image'])? '`image` = "' . $this->db->escape($data['image']) . '",' : '').' `column` = "' . (int)$data['column'] . '",'.(isset($data['category_layout'])? '`layout_id` ="'.(int)$data['category_layout'].'",' : '').' `sort_order` = "' . (int)$data['sort_order'] . '", `status` = "' . (int)$data['status'] . '", `date_modified` = NOW() WHERE `category_id` = "' . (int)$category_id . '"');
		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");
		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");
				$path = array();
				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}
				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");
				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}
				// Combine the paths with a new level
				$level = 0;
				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");
					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");
			// Fix for records with no paths
			$level = 0;
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
				$level++;
			}
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		$this->cache->delete('category');
	}
	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");
		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->cache->delete('category');
	}
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");
		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");
			// Fix for records with no paths
			$level = 0;
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
				$level++;
			}
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");
			$this->repairCategories($category['category_id']);
		}
	}
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path, c.seourl AS keyword FROM " . DB_PREFIX . "category c WHERE c.category_id = '" . (int)$category_id . "'");
		return $query->row;
	}
	public function getCategories($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "category`";
		$wh = ' WHERE ';
		if (!empty($data['filter_name'])) {
			$sql .= $wh . "`name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$wh = '';
		}
		if (isset($data['parent_id'])) {
			$sql .= $wh . "`parent_id` = '" . (int)$data['parent_id'] . "'";
		}
		$sql .= " ORDER BY `name`";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getCategoryPaths($category_id = 0) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "' ORDER BY `level`");
		return $query->rows;
	}
	public function getCategoryDescriptions($category_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category_id . "'");
		return $query->row;
	}
	public function getCategoryFilters($category_id) {
		$query = $this->db->query("SELECT `filter_id` FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		return $query->rows;
	}
	public function getCategoryLayouts($category_id) {
		$query = $this->db->query("SELECT `layout_id` FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int)$category_id . "'");
		if($query->row) return $query->row['layout_id'];
    else return FALSE;
	}
	public function getTotalCategories($parent_id = -1) {
     if($parent_id < 0) $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");
	 else $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE `parent_id` = '". (int)$parent_id . "'");
		return $query->row['total'];
	}
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "category` WHERE layout_id = '" . (int)$layout_id . "'");
		return $query->row['total'];
	}
}