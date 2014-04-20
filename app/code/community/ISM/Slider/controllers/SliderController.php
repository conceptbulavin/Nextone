<?php

/**
 * ISM Slider admin index controller
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_SliderController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('slider/index')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Slider Manager'), Mage::helper('adminhtml')->__('Items Manager'));
        $this->renderLayout();
    }

    /**
     *
     */
    public function editAction()
    {
        $sliderId = $this->getRequest()->getParam('id');
        $sliderModel = Mage::getModel('slider/slider')->load($sliderId);
        $sliderModel->getSlides();

        if ($sliderModel->getId() || $sliderId == 0) {

            Mage::register('slider_data', $sliderModel);

            $this->loadLayout();
            $this->_setActiveMenu('slider/index');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Slider Manager'), Mage::helper('adminhtml')->__('Slider Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();

        } else {

            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Slider does not exist'));
            $this->_redirect('*/*/');

        }
    }

    /**
     *
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     *
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {

            try {

                $post_data = $this->getRequest()->getPost();
                $slider_id = $this->getRequest()->getParam('id');
                $slide_model = Mage::getModel('slider/slider');
                $destFolder = rtrim(BP, '/\\') . '/media/ismslider/';
                $timeMarker = time();

                foreach ($post_data['slides'] as $slideId => $values) {
                    if (!empty($values['deleteimage'])) {
                        @unlink($destFolder . $values['deleteimage']);
                        @unlink($destFolder . 'thumb-' . $values['deleteimage']);
                        $post_data['slides'][$slideId]['image'] = '';
                    }
                }
                foreach ($_FILES['slides']['name'] as $slideId => $values) {
                    if (empty($values['image'])) {
                        continue;
                    }
                    if (!is_writable($destFolder)) {
                        Mage::getSingleton('adminhtml/session')->addError('Destination folder is not writable or does not exists.');
                        continue;
                    }
                    $fileName = Varien_File_Uploader::getCorrectFileName($values['image']);
                    $destFile = $destFolder . $timeMarker . '_' . $fileName;
                    $result = move_uploaded_file($_FILES['slides']['tmp_name'][$slideId]['image'], $destFile);
                    if ($result) {
                        chmod($destFile, 0644);
                        $imageProcessor = new Varien_Image($destFile);
                        $imageProcessor->keepAspectRatio(true);
                        $imageProcessor->keepFrame(true);
                        $imageProcessor->keepTransparency(true);
                        $imageProcessor->constrainOnly(false);
                        $imageProcessor->backgroundColor(array(255, 255, 255));
                        $imageProcessor->quality(90);
                        $imageProcessor->resize(172, 60);
                        $imageProcessor->save($destFolder, 'thumb-' . $timeMarker . '_' . $fileName);
                        $post_data['slides'][$slideId]['image'] = $timeMarker . '_' . $fileName;
                        chmod($destFolder, 'thumb-' . $timeMarker . '_' . $fileName, 0644);

                    } else {
                        $post_data['slides'][$slideId]['image'] = '';
                        Mage::getSingleton('adminhtml/session')->addError('File ' . $fileName . ' was not uploaded.');
                    }
                }
                $slide_model->setId($slider_id)->setData($post_data);

                if ($slider_id !== null) {
                    $slide_model->setModifiedTime(new Zend_Db_Expr('NOW()'));
                } else {
                    $slide_model->setCreatedTime(new Zend_Db_Expr('NOW()'));
                }

                $slide_model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Slider was successfully saved')
                );

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $slide_model->getId()));
                    return;
                }

                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     *
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {

            try {
                $slider_model = Mage::getModel('slider/slider');

                $slider_model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slide has been successfully deleted'));
                $this->_redirect('*/*/');

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * @param $postData
     * @return mixed
     */
    public function fileUpload($postData)
    {

        if (isset($_FILES['image']['name']) and (file_exists($_FILES['image']['tmp_name']))) {
            try {
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));

                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $path = Mage::getBaseDir('media') . DS;

                $uploader->save($path, $_FILES['image']['name']);

                $postData['image'] = $_FILES['image']['name'];

                return $postData;

            } catch (Exception $e) {

            }
        }
        return $postData;
    }

    /**
     * Chooser Source action
     */

    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $this->getLayout()->createBlock('slider/adminhtml_slider_widget_chooser', '', array(
            'id' => $uniqId,
        ));
        $this->getResponse()->setBody($pagesGrid->toHtml());
    }

}