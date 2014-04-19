<?php
/**
 * MagenMarket.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Edit or modify this file with yourown risk.
 *
 * @category    Extensions
 * @package     Ma2_Manufacturers Free
 * @copyright   Copyright (c) 2013 MagenMarket. (http://www.magenmarket.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
/* $Id: DefaultController.php 2 2014-01-13 07:35:22Z linhnt $ */

class Ma2_Manufacturers_Adminhtml_DefaultController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('ma2/manufacturers/manufacturers_manager')
			->_addBreadcrumb(Mage::helper('manufacturers')->__('Manufacturers Manager'), Mage::helper('manufacturers')->__('Manufacturers Manager'));

		return $this;
	}

	public function indexAction() {
    $this->_initAction();
    $this->renderLayout();
	}
  
	public function saveAction()
  {
		if ($data = $this->getRequest()->getPost())
    {
    
      if(isset($_FILES) && count((array)$_FILES)){
        foreach((array)$_FILES as $manid => $image){
          if (!empty($image['name']) && file_exists($image['tmp_name'])){
            try {
              $uploader = new Varien_File_Uploader($manid);
              $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
              $uploader->setAllowRenameFiles(false);
              
              $uploader->setFilesDispersion(false);
             
              $path = Mage::getBaseDir('media') . DS . 'manufacturers' . DS;
                
              $imageFullName = $uploader->getCorrectFileName($image['name']);
              
              $uploader->save($path, $imageFullName);
              
              $imagePath = Mage::getBaseDir('media') . DS . 'manufacturers' . DS . $imageFullName;
              
              // if resize              
              if (Mage::getStoreConfig('manufacturers/general/thumb_upload_resize') == 1 && file_exists($imagePath))
              {
                $thumbW = intval(Mage::getStoreConfig('manufacturers/general/thumb_w')) > 0 ? intval(Mage::getStoreConfig('manufacturers/general/thumb_w')) : 120;
                $thumbH = intval(Mage::getStoreConfig('manufacturers/general/thumb_h')) > 0 ? intval(Mage::getStoreConfig('manufacturers/general/thumb_h')) : 60;
                
                $imageThumbPath = Mage::getBaseDir('media') . DS . 'manufacturers' . DS . 'resized'. DS . $imageFullName;
                
                if (file_exists($imageThumbPath)) @unlink($imageThumbPath);
                
                $imageObj = new Varien_Image($imagePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(TRUE);
                $imageObj->keepTransparency(TRUE);
                $imageObj->keepFrame(FALSE);
                $imageObj->resize($thumbW, $thumbH);
                $imageObj->save($imageThumbPath);
                
                Mage::getModel('core/config')->saveConfig('manufacturers/image/'.$manid, 'manufacturers/resized/' . $imageFullName);
              }
              else {
                Mage::getModel('core/config')->saveConfig('manufacturers/image/'.$manid, 'manufacturers/'.$imageFullName);
              }
              
            }catch(Exception $e) {
           
            }
          }
        }
      }
      
      // if some is checked as delete
      foreach ($data as $k => $v){
        if (isset($v['delete']) && $v['delete'] == 1) Mage::getModel('core/config')->saveConfig('manufacturers/image/'.$k, null);
      }
      
    }
    $this->_redirect('*/*/index');
    return;
	}

	
}