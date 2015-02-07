<?php
class ModelDesignBanner extends Model {
	
	// Добавление баннера
	public function addBanner($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "banner` SET `name` = '" . $this->db->escape($data['name']) . "', `status` = '" . (int)$data['status'] . "'");
	
		$banner_id = $this->db->getLastId();
	
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_id . "', `title` = '" .  $this->db->escape($banner_image['title']) . "', `link` = '" .  $this->db->escape($banner_image['link']) . "', `image` = '" .  $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
			}
		}		
	}
	
	// Редактирование баннера
	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "banner` SET `name` = '" . $this->db->escape($data['name']) . "', `status` = '" . (int)$data['status'] . "' WHERE `banner_id` = '" . (int)$banner_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE `banner_id` = '" . (int)$banner_id . "'");
			
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_id . "', `title` = '" .  $this->db->escape($banner_image['title']) . "', `link` = '" .  $this->db->escape($banner_image['link']) . "', `image` = '" .  $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
			}
		}			
	}
	
	// Удаление баннера
	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
	}
	
	// Получить баннер по его id
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		
		return $query->row;
	}
	
	// Получить баннер по параметру	
	public function getBanners($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "banner`";
		
		$sort_data = array('name','status');	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else $sql .= " ORDER BY `name`";
		
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
	}
	
	// Получение картинок баннера	
	public function getBannerImages($banner_id) {

		$banner_image_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image` WHERE `banner_id` = '" . (int)$banner_id . "' ORDER BY `sort_order` ASC");	
		return $banner_image_query->rows;
	}
	
	// Получить колличество баннеров
	public function getTotalBanners() {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "banner`");
		return $query->row['total'];
	}	
}