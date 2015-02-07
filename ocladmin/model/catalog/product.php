<?php
class ModelCatalogProduct extends Model {
	// Добавление нового товара
	public function addProduct($data) {
		
		$data['images'] = '';
		if (isset($data['product_images'])) {
			foreach ($data['product_images'] as $product_image) {
				if(empty($data['images']))$data['images'] = $product_image['image'];
				else $data['images'] .= SEP1 . $product_image['image'];
			}
		}
		
		$data['related'] = '';
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				if(empty($data['related'])) $data['related'] = $related_id;
				else $data['related'] .= SEP1 . $related_id;
			}
		}
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET `category_id` = '" . (int)$data['product_category'][0] . "', `name` = '" . $this->db->escape($data['name']) . "',`model` = '" . $this->db->escape($data['model']) . "',	`description` = '" . $this->db->escape($data['description']) . "',	`sku` = '" . $this->db->escape($data['sku']) . "', `mpn` = '" . $this->db->escape($data['mpn']) . "', `video` = '" . $this->db->escape($data['video']) . "',	`files` = '" . $this->db->escape($data['files']) . "',	`infofile` = '" . $this->db->escape($data['infofile']) . "', `images` = '" . $this->db->escape($data['images']) . "', `tag` = '" . $this->db->escape($data['tag']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "', `h1` = '" . $this->db->escape($data['h1']) . "', `location` = '" . $this->db->escape($data['location']) . "', `manufacturer_id` = '" . (int)$data['manufacturer_id'] . "', `quantity` = '" . (int)$data['quantity'] . "', `stock_status_id` = '" . (int)$data['stock_status_id'] . "', `minimum` = '" . (int)$data['minimum'] . "', `subtract` = '" . (int)$data['subtract'] . "', `qpbox` = '" . (int)$data['qpbox'] . "', `date_available` = '" . $this->db->escape($data['date_available']) . "', `shipping` = '" . (int)$data['shipping'] . "', `price` = '" . (float)$data['price'] . "', `special` = '" . (float)$data['special'] . "', `date_start` = '" . $this->db->escape($data['date_start']) . "', `date_end` = '" . $this->db->escape($data['date_end']) . "', `weight` = '" . (float)$data['weight'] . "', `weight_class_id` = '" . (int)$data['weight_class'] . "', `length` = '" . (float)$data['length'] . "', `width` = '" . (float)$data['width'] . "', `height` = '" . (float)$data['height'] . "', `length_class` = '" . (int)$data['length_class_id'] . "', `related` = '" . $this->db->escape($data['related']) . "', `layout_id` = '" . (int)$data['product_layout'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (int)$data['status'] . "', `seourl` = '" . $this->db->escape($data['seourl']) . "', `date_added` = NOW()");
		
		$product_id = $this->db->getLastId();		

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
									
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', text = '" .  $this->db->escape($product_attribute['text']) . "'");
				}
			}
		}
	
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
						$product_option_id = $this->db->getLastId();
					
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						} 
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

						
		if (isset($data['seourl'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['seourl']) . "'");
		}

		$this->cache->delete('product');
	}
	
	// Редактирование товара
	public function editProduct($product_id, $data) {
		if(!isset($data['files'])) $data['files'] = '';
		if(!isset($data['video'])) $data['video'] = '';
		if(!isset($data['infofile'])) $data['infofile'] = '';
		if(!isset($data['shipping'])) $data['shipping'] = '';
		$data['images'] = '';
		if (isset($data['image'])) $data['images'] = $data['image'];
		if (isset($data['product_images'])) {
			foreach ($data['product_images'] as $product_image) {
				if(empty($data['images']))$data['images'] = $product_image['image'];
				else $data['images'] .= SEP1 . $product_image['image'];
			}
		}
		
		$data['related'] = '';
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				if(empty($data['related'])) $data['related'] = $related_id;
				else $data['related'] .= SEP1 . $related_id;
			}
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET 
		`category_id` = '" . (int)$data['product_category'][0] . "', 
		`name` = '" . $this->db->escape($data['name']) . "',
		`model` = '" . $this->db->escape($data['model']) . "',
		`description` = '" . $this->db->escape($data['description']) . "',
		`sku` = '" . $this->db->escape($data['sku']) . "',  
		`mpn` = '" . $this->db->escape($data['mpn']) . "',
		`video` = '" . $this->db->escape($data['video']) . "',
		`files` = '" . $this->db->escape($data['files']) . "',
		`infofile` = '" . $this->db->escape($data['infofile']) . "',
		`images` = '" . $this->db->escape($data['images']) . "',
		`tag` = '" . $this->db->escape($data['tag']) . "',
		`meta_title` = '" . $this->db->escape($data['meta_title']) . "', 
		`meta_description` = '" . $this->db->escape($data['meta_description']) . "',
		`meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "',
		`h1` = '" . $this->db->escape($data['h1']) . "', 
		`location` = '" . $this->db->escape($data['location']) . "',
		`manufacturer_id` = '" . (int)$data['manufacturer_id'] . "', 
		`quantity` = '" . (int)$data['quantity'] . "',
		`stock_status_id` = '" . (int)$data['stock_status_id'] . "',
		`minimum` = '" . (int)$data['minimum'] . "', 
		`subtract` = '" . (int)$data['subtract'] . "',
		`qpbox` = '" . (int)$data['qpbox'] . "', 
		`date_available` = '" . $this->db->escape($data['date_available']) . "',
		`shipping` = '" . (int)$data['shipping'] . "', 
		`price` = '" . (float)$data['price'] . "',
		`special` = '" . (float)$data['special'] . "',
		`date_start` = '" . $this->db->escape($data['date_start']) . "',
		`date_end` = '" . $this->db->escape($data['date_end']) . "',
		`weight` = '" . (float)$data['weight'] . "',
		`weight_class` = '" . (int)$data['weight_class_id'] . "', 
		`length` = '" . (float)$data['length'] . "', 
		`width` = '" . (float)$data['width'] . "', 
		`height` = '" . (float)$data['height'] . "', 
		`length_class` = '" . (int)$data['length_class_id'] . "',
		`related` = '" . $this->db->escape($data['related']) . "',
		`layout_id` = '" . (int)$data['product_layout'] . "',
		`sort_order` = '" . (int)$data['sort_order'] . "',
		`status` = '" . (int)$data['status'] . "',
		`seourl` = '" . $this->db->escape($data['seourl']) . "', 
		`date_modified` = NOW() WHERE `product_id` = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
									
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', text = '" .  $this->db->escape($product_attribute['text']) . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
				
						$product_option_id = $this->db->getLastId();
					
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}					
			}
		}
		
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}
		
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		if ($data['seourl']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['seourl']) . "'");
		}
						
		$this->cache->delete('product');
	}
	
	// Копирование товара
	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;
			
			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';
						
			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
			$data = array_merge($data, array('product_filter' => $this->getProductFilters($product_id)));
			$data = array_merge($data, array('product_images' => $this->getProductImages($product_id)));		
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));			
			$this->addProduct($data);
		}
	}
	
	// Удаление товара
	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		$this->cache->delete('product');
	}
	
	// Получить товар по его id
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT p.*, m.name AS manufacturer, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= '".DNOW."'");
		
		$query->row['images'] = (isset($query->row['images']))? explode(SEP1,$query->row['images']) : array();	
		if(isset($query->row['files'])){
			$i = explode(SEP2,$query->row['files']);
			$query->row['files'] = array();
			while(count($i) > 0){
				$query->row['files'][] = array('title'=>array_shift($i),'link'=>array_shift($i));
			}
		}
		
		if ($query->num_rows) {
			$query->row['rating'] = round($query->row['rating']);
			if(!$query->row['reviews']) $query->row['reviews'] = 0;
			return $query->row;
		} else return false;
	}
	
	// Получить список товаров по параметрам
	public function getProducts($data = array()) {
	$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, p.special AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " WHERE p.status = '1' AND p.date_available <= '".DNOW."'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "p.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR p.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "p.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'p.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'p.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY p.price";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else $sql .= " ORDER BY p.sort_order";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(p.name) DESC";
		} else $sql .= " ASC, LCASE(p.name) ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;

			if ($data['limit'] < 1) $data['limit'] = 20;

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = '" . (int)$category_id . "' ORDER BY p.name ASC");
								  
		return $query->rows;
	} 
	
	public function getProductDescriptions($product_id) {	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
				
		return $query->rows;
	}
		
	public function getProductCategories($product_id) {	
		$query = $this->db->query("SELECT `category_id` FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}
	
	public function getProductFilters($product_id) {
		$product_filter_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}
				
		return $product_filter_data;
	}
	
	public function getProductAttributes($product_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();
		
		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) WHERE po.product_id = '" . (int)$product_id . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();	
				
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");
				
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],						
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']					
				);
			}
				
			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],				
				'value'                => $product_option['value'],
				'required'             => $product_option['required']				
			);
		}
		
		return $product_option_data;
	}
			
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");
		if($query->row['images']) return explode(SEP1,$query->row['images']);
		return array();
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "' AND `special` > 0");
		
		return $query->rows;
	}

	public function getProductLayouts($product_id) {
		$query = $this->db->query("SELECT `layout_id` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'");		
		return $query->row['layout_id'];
	}
	
	public function getProductRelated($product_id) {
		$product_related_data = array();
		
		$query = $this->db->query("SELECT `related` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'");
		$related = array();
		if($query->row['related']) $related = explode(SEP1,$query->row['related']);
		
		return $related;
	}
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p2c.product_id = p.product_id)";
		
    $wh = ' WHERE'; 			
		if (!empty($data['filter_name'])) {
			$sql .= $wh . " p.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
      $wh = ' AND';
		}

		if (!empty($data['filter_model'])) {
			$sql .= $wh . " p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
      $wh = ' AND';
		}
		
		if (!empty($data['filter_manufacturer'])) {
			$sql .= $wh . " p.manufacturer_id ='" . ((int)$data['filter_manufacturer']) . "'";
      $wh = ' AND';
		}
		
		if (isset($data['filter_category']) && $data['filter_quantity'] !== null) {
			$sql .= $wh . " p2c.category_id = '" . (int)$data['filter_category'] . "'";
      $wh = ' AND ';
		}
		
		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= $wh . " p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
		
	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` WHERE `stock_status_id` = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE `attribute_id` = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}	
	
	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_option` WHERE `option_id` = '" . (int)$option_id . "'");

		return $query->row['total'];
	}	
	
	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsOutOfStock() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` WHERE `status` <= 0");

		return $query->row['total'];
	}	
}