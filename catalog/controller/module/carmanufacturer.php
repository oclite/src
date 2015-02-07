<?php
class ControllerModuleCarmanufacturer extends Controller {
    public function index($setting) {
        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');        
        $this->document->addScript('catalog/view/javascript/jquery/flexslider/jquery.flexslider-min.js');
        $data['limit'] = $setting['limit'];
        $data['scroll'] = $setting['scroll'];
        $data['box'] = $setting['box'];
        $data['heading_title'] = $setting['title'];
        
        $data['carmanufacturers'] = array();
        
        $results = $this->model_catalog_manufacturer->getmanufacturers();
        
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
            } else continue;
                        
            $data['carmanufacturers'][] = array(
                'name'  => $result['name'],
                'thumb' => $image,
                'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
            );
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carmanufacturer.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/carmanufacturer.tpl',$data);
        } else {
            return $this->load->view('default/template/module/carmanufacturer.tpl',$data);
        }
    }
}
