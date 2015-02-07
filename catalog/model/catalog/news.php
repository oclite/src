<?php
class ModelCatalogNews extends Model
{

  public function updateViewed($news_id)
  {
    $this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = (viewed + 1) WHERE news_id = '" . (int)$news_id . "'");
  }

  public function getNewsStory($news_id)
  {
    $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "' AND status = '1'");

    return $query->row;
  }

  public function getNews($data)
  {
    $sql = "SELECT * FROM " . DB_PREFIX . "news WHERE status = '1' ORDER BY date_added DESC";

    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) $data['start'] = 0;
      if ($data['limit'] < 1) $data['limit'] = 10;

      $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
    }

    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function getNewsShorts($limit)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news WHERE status = '1' ORDER BY date_added DESC LIMIT " . (int)$limit);

    return $query->rows;
  }

  public function getTotalNews()
  {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news WHERE status = '1'");

    if ($query->row) return $query->row['total'];
    else return FALSE;
  }
}

