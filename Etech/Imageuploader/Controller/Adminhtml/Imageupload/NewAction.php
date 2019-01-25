<?php
namespace Etech\Imageuploader\Controller\Adminhtml\Imageupload;
use Magento\Backend\App\Action;
class NewAction extends \Magento\Backend\App\Action
{
     public function execute()
    {
		$this->_forward('edit');
    }
}
