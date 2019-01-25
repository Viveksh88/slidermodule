<?php
/**
 * Copyright © 2015 Etech . All rights reserved.
 */
namespace Etech\Imageuploader\Block\Frontimage;
use Etech\Imageuploader\Block\BaseBlock;
class Index extends BaseBlock
{
	public function getCollection(){

		return $this->_model->create()->getCollection();
	
	}
	public function getImageurl($imagePath){
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$imagePath;
	}
	public function getBaseurl(){
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

	}
	// public $hello='Hello World';
	
}
