<?php
namespace Etech\Imageuploader\Block\Adminhtml;
class Imagemodel extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_imagemodel';/*block grid.php directory*/
        $this->_blockGroup = 'Etech_Imageuploader';
        $this->_headerText = __('Imagemodel');
        $this->_addButtonLabel = __('Add Image'); 
        parent::_construct();
		
    }
}
