<?php
class ControllerProductPrice extends Controller
{
  public function index()
  {
    $this->load->language('product/price');
    $text_store = $this->language->get('text_store');
    $text_phone = $this->language->get('text_phone');
    $text_email      = $this->language->get('text_email');
    $text_price_info = $this->language->get('text_price_info');
    $text_cdate      = $this->language->get('text_cdate');
    $col_product     = $this->language->get('col_product');
    $col_box         = $this->language->get('col_box');
    $col_unit_price  = $this->language->get('col_unit_price');
    $col_price       = $this->language->get('col_price');
    
    $this->load->model('catalog/price');
    $filename = substr(HTTP_SERVER,7);
    $filename = substr($filename,0,strpos($filename,'/')).'-price.xlsx';
    $urlxls = HTTP_SERVER.'content/price/'.$filename;
    $filename = DIR_CONTENT.'price/'. $filename;

    if(!is_file($filename))
    {	
			$results = $this->model_catalog_price->getPrice();
			$sheet = '';
			
			$emails = $this->config->get('config_shop_email');
			
			$company_name = $this->config->get('config_name');;
			$company_contact = nl2br($this->config->get('config_address'))."\n". $text_phone . ' ' .	$this->config->get('config_telephone')."\n".$text_email.' '.$emails."\n".$text_store.' '. HTTP_SERVER;
			$shstr = '<si><r><rPr><b/><sz val="14"/><rFont val="Calibri"/><family val="2"/><charset val="204"/></rPr><t>'.$company_name.'</t></r><r><rPr><b/><sz val="10"/><rFont val="Calibri"/><family val="2"/><charset val="204"/></rPr><t xml:space="preserve">
</t></r><r><rPr><sz val="10"/><rFont val="Calibri"/><family val="2"/><charset val="204"/></rPr><t>'.$company_contact.'</t></r></si><si><t>'.$text_price_info."\n".$text_cdate.date('d.m.Y').'</t></si><si><t>'.$col_product.'</t></si><si><t>'.$col_box.'</t></si><si><t>'.$col_unit_price.'</t></si><si><t>'.$col_price.'</t></si>';
			$rowid = 3;
			$strid = 6;
			$itemcount = 0;
	    foreach ($results as $result) {
	    	if(empty($result['name'])) continue;
	    	$sheet .= '<row r="'.$rowid.'" spans="1:4">';
	    	
	    	// Наименование
	    	$shstr .= '<si><t>'.$result['name'].'</t></si>';
	    	$sheet .= '<c r="A'.$rowid.'" s="3" t="s"><v>'.($strid++).'</v></c>';
	    	
	    	// Упаковка
	    	if(!empty($result['qpbox']) && $result['qpbox'] > 1){
	    		$sheet .= '<c r="B'.$rowid.'" s="20"><v>'.$result['qpbox'].'</v></c>';
	    	}
	    	else $sheet .= '<c r="B'.$rowid.'" s="20"><v>1</v></c>';
	    	
	    	$price = ((float)$result['special'] > 0)? (float)$result['special'] : (float)$result['price'];
	    	
	    	// Цена за единицу
	    	if(!empty($result['qpbox']) && $result['qpbox'] > 1){
	    		$sheet .= '<c r="C'.$rowid.'" s="17"><v>'.($price/$result['qpbox']).'</v></c>';
	    	} 
	    	else $sheet .= '<c r="C'.$rowid.'" s="17"><v>'.$price.'</v></c>';
	    	
	  		// Цена
	  		if($price > 0){
	  			$sheet .= '<c r="D'.$rowid.'" s="17"><v>'.$price.'</v></c>';
	  		} else $sheet .= '<c r="D'.$rowid.'" s="17"/>'; 
	  		
	    	$sheet .= '</row>';
	    	$rowid++;
	    }
	    
	    if (!copy(DIR_CONTENT.'price/'.'price.xlsx', $filename)) die('Error create price');
	    $shstr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.$strid.'" uniqueCount="'.($strid-1).'">'.$shstr.'</sst>';
			$sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheetPr><pageSetUpPr fitToPage="1"/></sheetPr><dimension ref="A1:D'.($rowid).'"/><sheetViews><sheetView tabSelected="1" workbookViewId="0"><pane ySplit="2" topLeftCell="A3" activePane="bottomLeft" state="frozen"/><selection pane="bottomLeft" activeCell="A13" sqref="A13"/></sheetView></sheetViews><sheetFormatPr defaultRowHeight="15"/><cols><col min="1" max="1" width="75" customWidth="1"/><col min="2" max="2" width="18.42578125" customWidth="1"/><col min="3" max="3" width="11.42578125" customWidth="1"/></cols><sheetData><row r="1" spans="1:4" ht="82" customHeight="1" thickBot="1"><c r="A1" s="16" t="s"><v>0</v></c><c r="B1" s="12" t="s"><v>1</v></c><c r="C1" s="12"/><c r="D1" s="12"/></row><row r="2" spans="1:4" ht="15" customHeight="1"><c r="A2" s="1" t="s"><v>2</v></c><c r="B2" s="1" t="s"><v>3</v></c><c r="C2" s="1" t="s"><v>4</v></c><c r="D2" s="2" t="s"><v>5</v></c></row>'.$sheet.'</sheetData><mergeCells count="1"><mergeCell ref="B1:D1"/></mergeCells><pageMargins left="0.48" right="0.3" top="0.48" bottom="0.74803149606299213" header="0.31496062992125984" footer="0.31496062992125984"/><pageSetup paperSize="9" scale="85" fitToHeight="0" orientation="portrait" verticalDpi="300" r:id="rId1"/><headerFooter><oddFooter>&amp;C&amp;P</oddFooter></headerFooter><drawing r:id="rId2"/></worksheet>';
			$za = new ZipArchive();
			if($za->open($filename) === TRUE){
				$za->deleteName('xl/worksheets/sheet1.xml');
				$za->deleteName('xl/sharedStrings.xml');
				$za->addFromString('xl/worksheets/sheet1.xml',$sheet);
				$za->addFromString('xl/sharedStrings.xml',$shstr);
				$za->close();
			}
			else die('error');
	  }
	  $this->response->redirect($urlxls);
  }

}
