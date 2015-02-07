<?php
class ModelCatalogProduct extends Model {
// Прибавление кол-во просмотров товара
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
}
// Получить данные из складов
public function getStocksPrice($product_id) {
	$query = $this->db->query("SELECT pp.*,s.name As 'stock_name',s.term FROM " . DB_PREFIX . "product_price AS pp LEFT JOIN " . DB_PREFIX . "stocks AS s ON pp.stock_id = s.stock_id WHERE product_id='".(int)$product_id."'");
	return $query->rows;
}
// Получение данных одного товара
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT p.*, m.name AS manufacturer, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= '".DNOW."'");
		if(!empty($query->row['images'])) {
			$i = explode(SEP1,$query->row['images'],2);
			$query->row['image'] = $i[0];
		}
		else $query->row['image'] = '';
		if(!empty($query->row['files'])){
			$files = $query->row['files'];
			$query->row['files'] = array();
			if(strpos($files,SEP2)){
				$i = explode(SEP2,$files);
				while(count($i)){
					$query->row['files'][] = array('title'=>array_shift($i),'link'=>array_shift($i));
				}
			}
			else $query->row['files'][] = array('title' => $files,'link' => $files);
		}
		if ($query->num_rows) {
			$query->row['rating'] = round($query->row['rating']);
			if(!$query->row['reviews']) $query->row['reviews'] = 0;
			return $query->row;
		} else return false;
	}
// Получение данных товаров
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
		} else {
			$sql .= " ORDER BY p.sort_order";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(p.name) DESC";
		} else {
			$sql .= " ASC, LCASE(p.name) ASC";
		}
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
// Товары со скидкой
public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT p.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p  WHERE p.status = '1' AND p.date_available <= '".DNOW."' AND `special` > 0 ";
		$sort_data = array(
			'p.name',
			'p.model',
			'p.special',
			'rating',
			'p.sort_order'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'p.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else $sql .= " ORDER BY " . $data['sort'];
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
// Получение списка последных добавленых товаров
	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p WHERE p.status = '1' AND p.date_available <= '".DNOW."' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			$this->cache->set('product.latest.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		return $product_data;
	}
// Получение списка популярных товаров
	public function getPopularProducts($limit) {
		$product_data = array();
		$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product` WHERE `status`='1' AND `date_available` <= '".DNOW."' ORDER BY `viewed` DESC, `date_added` DESC LIMIT " . (int)$limit);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
		return $product_data;
	}
// Получить рекомендуемые товары
	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
		if (!$product_data) {
			$product_data = array();
			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= '".DNOW."' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			$this->cache->set('product.bestseller.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		return $product_data;
	}
// Получение списка атрибутов товара
	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();
		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, ag.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, ag.name");
		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();
			$product_attribute_query = $this->db->query("SELECT a.attribute_id, a.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' ORDER BY a.sort_order, a.name");
			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}
			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}
		return $product_attribute_group_data;
	}
// Получение опции товара
	public function getProductOptions($product_id) {
		$product_option_data = array();
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) WHERE po.product_id = '" . (int)$product_id . "' ORDER BY o.sort_order");
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order");
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
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
// Получение дополнительных картинок товара
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT `images` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'");
		$images = explode(';',$query->row['images']);
		if(count($images) > 1) return $images;
		return array();
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT `layout_id` FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		if ($query->num_rows) return $query->row['layout_id'];
		else return 0;
	}
// Получение категории по товару
	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
// Получение кол-во товаров по icount
	public function getTotalProductsIc($data = array()) {
		$total = 0;
		if(isset($data['filter_category_id'])){
			$sql = "SELECT `icount` FROM `" . DB_PREFIX . "category` WHERE `category_id`=".intval($data['filter_category_id']);
			$query = $this->db->query($sql);
			if(isset($query->row['icount'])) $total = intval($query->row['icount']);
			else $total = 0;
		}
		return $total;
	}
// Получить кол-во товаров
	public function getTotalProducts($data = array()) {
/*		$total = 0;
		if(isset($data['filter_category_id'])){
			$sql = "SELECT `icount` FROM `" . DB_PREFIX . "category` WHERE `category_id`=".intval($data['filter_category_id']);
			$query = $this->db->query($sql);
			if(isset($query->row['icount'])) $total = intval($query->row['icount']);
			else $total = 0;
		}
		return $total; */
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
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
				$sql .= "p.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
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
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT `product_id`) AS total FROM `" . DB_PREFIX . "product` WHERE `status` = '1' AND `date_available` <= '".DNOW."' AND ((`date_start` = '0000-00-00' OR `date_start` < '".DNOW."') AND (`date_end` = '0000-00-00' OR `date_end` > '".DNOW."'))");
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else return 0;
	}
}