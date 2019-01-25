<?php
namespace Etech\Imageuploader\Controller\Adminhtml\Imageupload;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_objectManager->create('Etech\Imageuploader\Model\Imagemodel');
		
             if(isset($_FILES['uploadedimage']['name']) && $_FILES['uploadedimage']['name'] != '') {
				try {
					    $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', array('fileId' => 'uploadedimage'));
						$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(true);
						$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
							->getDirectoryRead(DirectoryList::MEDIA);
						$result = $uploader->save($mediaDirectory->getAbsolutePath('slider/images'));
						unset($result['tmp_name']);
						unset($result['path']);
                        $imagePath = 'slider/images'.$result['file'];
                        $data['uploaded image'] = $imagePath;
						// $data['image'] = $result['file'];
				} catch (Exception $e) {
					$data['image'] = $_FILES['uploadedimage']['name'];
				}
			}
			else{
				$data['uploaded image'] = $data['uploadedimage']['value'];
			} 
			$id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            $data['image name'] = $data['imagename'];
			
            $model->setData($data);
			
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Frist Grid Has been Saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
