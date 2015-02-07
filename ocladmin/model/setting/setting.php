<?php
class ModelSettingSetting extends Model {
	public function getSetting($group) {
		$data = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = '" . $this->db->escape($group) . "'");
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else $data[$result['key']] = unserialize($result['value']);
		}
		return $data;
	}
	public function editSetting($group, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");
		foreach ($data as $key => $value) {
			if($key == 'config_custom_footer')
			{
				file_put_contents(DIR_CONTENT . 'data/footer.html',$value);
			}elseif($key == 'config_custom_header'){
				file_put_contents(DIR_CONTENT . 'data/header.html',$value);
			}elseif (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}
	public function deleteSetting($group) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");
	}
	public function editSettingValue($group = '', $key = '', $value = '') {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND serialized = '1'");
		}
	}
}