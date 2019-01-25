<?php
namespace Etech\Imageuploader\Block\Adminhtml\Imagemodel\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_imagemodel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Imagemodel Information'));
    }
}