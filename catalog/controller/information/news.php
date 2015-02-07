<?php
class ControllerInformationNews extends Controller {
	public function index() {
		$this->language->load('information/news');
		$this->load->model('catalog/news');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array('href' => $this->url->link('common/home'), 'text' => $this->language->get('text_home'), 'separator' => false);
		$data['breadcrumbs'][] = array('href' => $this->url->link('information/new'), 'text' => $this->language->get('text_news'), 'separator' => false);
		$this->document->setTitle($this->language->get('text_news'));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$news_id = (isset($this->request->get['news_id'])) ? 1 * $this->request->get['news_id'] : 0;
		$news_info = $this->model_catalog_news->getNewsStory($news_id);
		if ($news_info) {
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$data['breadcrumbs'][] = array('href' => $this->url->link('information/news', 'news_id=' . $news_id), 'text' => $news_info['title'], 'separator' => $this->language->get('text_separator'));
			$this->document->setTitle($news_info['title']);
			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['meta_keyword']);
			$this->document->addLink($this->url->link('information/news', 'news_id=' . $news_id), 'canonical');
			$data['news_info'] = $news_info;
			$data['heading_title'] = $news_info['title'];
			$data['description'] = html_entity_decode($news_info['description']);
			$data['meta_keyword'] = html_entity_decode($news_info['meta_keyword']);
			$data['viewed'] = sprintf($this->language->get('text_viewed'), $news_info['viewed']);
			$data['addthis'] = $this->config->get('news_newspage_addthis');
			$data['min_height'] = $this->config->get('news_thumb_height');
			$this->load->model('tool/image');
			$data['image'] = ($news_info['image']) ? TRUE : FALSE;
			$data['thumb'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_thumb_width'), $this->config->get('news_thumb_height'));
			$data['popup'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_popup_width'), $this->config->get('news_popup_height'));
			$data['button_news'] = $this->language->get('button_news');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['news'] = $this->url->link('information/news');
			$data['continue'] = $this->url->link('common/home');
			if (isset($_SERVER['HTTP_REFERER'])) $data['referred'] = $_SERVER['HTTP_REFERER'];
			$data['refreshed'] = 'http://' . $_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI'];
			if (isset($data['referred'])) {
				$this->model_catalog_news->updateViewed($this->request->get['news_id']);
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
				$template = $this->config->get('config_template') . '/template/information/news.tpl';
			} else $template = 'default/template/information/news.tpl';
			$this->response->setOutput($this->load->view($template, $data));
		} else {
			$url = '';
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				$url.= '&page=' . $this->request->get['page'];
			} else $page = 1;
			$limit = $this->config->get('config_catalog_limit');
			$rdata = array('page' => $page, 'limit' => $limit, 'start' => $limit * ($page - 1),);
			$total = $this->model_catalog_news->getTotalNews();
			$pagination = new Pagination();
			$pagination->total = $total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/news', $url . '&page={page}', 'SSL');
			$data['pagination'] = $pagination->render();
			$news_data = $this->model_catalog_news->getNews($rdata);
			if ($news_data) {
				$this->document->setTitle($this->language->get('heading_title'));
				$data['breadcrumbs'][] = array('href' => $this->url->link('information/news'), 'text' => $this->language->get('heading_title'), 'separator' => $this->language->get('text_separator'));
				$data['heading_title'] = $this->language->get('heading_title');
				$this->document->addStyle('catalog/view/javascript/jquery/panels/main.css');
				$this->document->addScript('catalog/view/javascript/jquery/panels/utils.js');
				$data['text_more'] = $this->language->get('text_more');
				$data['text_posted'] = $this->language->get('text_posted');
				$chars = $this->config->get('news_headline_chars');
				$this->load->model('tool/image');
				foreach($news_data as $result) {
					$data['news_data'][] = array('id' => $result['news_id'], 'title' => $result['title'], 'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('news_thumb_width'), $this->config->get('news_thumb_height')), 'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $chars), 'href' => $this->url->link('information/news', 'news_id=' . $result['news_id']), 'posted' => date($this->language->get('date_format_short'), strtotime($result['date_added'])));
				}
				$data['button_continue'] = $this->language->get('button_continue');
				$data['continue'] = $this->url->link('common/home');
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
					$template = $this->config->get('config_template') . '/template/information/news.tpl';
				} else {
					$template = 'default/template/information/news.tpl';
				}
				$this->response->setOutput($this->load->view($template, $data));
			} else {
				$this->document->setTitle($this->language->get('text_error'));
				$this->document->breadcrumbs[] = array('href' => $this->url->link('information/news'), 'text' => $this->language->get('text_error'), 'separator' => $this->language->get('text_separator'));
				$data['heading_title'] = $this->language->get('text_error');
				$data['text_error'] = $this->language->get('text_error');
				$data['button_continue'] = $this->language->get('button_continue');
				$data['continue'] = $this->url->link('common/home');
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
				} else $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}