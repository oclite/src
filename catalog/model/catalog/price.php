<?php
class ModelCatalogPrice extends Model {	
// Получение товаров из каталога
	public function GetPrice() {
		$result = $this->db->query("SELECT p.* FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p.`product_id` = p2c.`product_id`) ORDER BY p2c.`category_id`,p.`manufacturer_id`");
	return $result->rows;		
	}

}
