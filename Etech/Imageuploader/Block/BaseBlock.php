<?php
/**
 * Copyright © 2015 Etech . All rights reserved.
 */
namespace Etech\Imageuploader\Block;
use Magento\Framework\UrlFactory;
class BaseBlock extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Etech\Imageuploader\Helper\Data
     */
	 protected $_devToolHelper;
	 
	 /**
     * @var \Magento\Framework\Url
     */
	 protected $_urlApp;
	 
	 /**
     * @var \Etech\Imageuploader\Model\Config
     */
	protected $_config;
	protected $_model;

    /**
     * @param \Etech\Imageuploader\Block\Context $context
	 * @param \Magento\Framework\UrlFactory $urlFactory
     */
	public function __construct( \Etech\Imageuploader\Block\Context $context,
	\Etech\Imageuploader\Model\ImagemodelFactory $model,
	\Magento\Store\Model\StoreManagerInterface $storeManager
	// \Etech\Imageuploader\Model\Imagemodel $imgdata
	)
    {
		$this->_storeManager=$storeManager;
		$this->_model = $model;	
        $this->_devToolHelper = $context->getImageuploaderHelper();
		$this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
		parent::__construct($context);
	
    }
	
	/**
	 * Function for getting event details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getEventDetails();
    }
	
	/**
     * Function for getting current url
	 * @return string
     */
	public function getCurrentUrl(){
		return $this->_urlApp->getCurrentUrl();
	}
	
	/**
     * Function for getting controller url for given router path
	 * @param string $routePath
	 * @return string
     */
	public function getControllerUrl($routePath){
		
		return $this->_urlApp->getUrl($routePath);
	}
	
	/**
     * Function for getting current url
	 * @param string $path
	 * @return string
     */
	public function getConfigValue($path){
		return $this->_config->getCurrentStoreConfigValue($path);
	}
	
	/**
     * Function canShowImageuploader
	 * @return bool
     */
	public function canShowImageuploader(){
		$isEnabled=$this->getConfigValue('imageuploader/module/is_enabled');
		if($isEnabled)
		{
			$allowedIps=$this->getConfigValue('imageuploader/module/allowed_ip');
			 if(is_null($allowedIps)){
				return true;
			}
			else {
				$remoteIp=$_SERVER['REMOTE_ADDR'];
				if (strpos($allowedIps,$remoteIp) !== false) {
					return true;
				}
			}
		}
		return false;
	}
	
}
