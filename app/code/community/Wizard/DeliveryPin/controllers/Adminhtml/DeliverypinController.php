<?php
/**
 * Magento GoTechy Admin Status Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Gotechy
 * @package    GT_MassStatus
 * @copyright  Copyright (c) 2014 GoTechy (http://www.gotechy.in)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 **/
 
class GT_DeliveryPin_Adminhtml_DeliverypinController extends Mage_Adminhtml_Controller_Action{

	
	public function indexAction(){
		$this->loadLayout();
		$this->_title($this->__('Add/Update'))->_title($this->__('Delivery PIN'));
 	    $this->_setActiveMenu('deliverypin/addupdate');
		$this->_addContent($this->getLayout()->createBlock('adminhtml/template')->setTemplate('deliverypin/addupdate.phtml'));

 	    
 	    $this->renderLayout();
	}
	
	public function uploadAction(){
		  $uploader = new Varien_File_Uploader('csvfile');
		  $uploader->setAllowedExtensions(array('csv'));
		  $uploader->setAllowRenameFiles(false);
		  $uploader->setFilesDispersion(false);
		  $path = Mage::getBaseDir().'/var/deliverypin';
		  $fileName = $_FILES['csvfile']['name'];
		  $ext    = explode('.', $fileName);
          $ext    = array_pop($ext);
		  //print_r($uploader->save($path, "pincodes.".$ext));

          $this->_getSession()->addSuccess($this->__("File saved successfully!"));
          $this->_redirect('*/deliverypin/index');
	}

	public function downloadAction(){
		$filepath = Mage::getBaseDir().'/var/deliverypin/pincodes.csv';
		if (! is_file ( $filepath ) || ! is_readable ( $filepath )) {
            $this->_getSession()->addSuccess($this->__("File not available!"));
            $this->_redirect('*/deliverypin/index');
        }
        $this->getResponse ()
                    ->setHttpResponseCode ( 200 )
                    ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
                     ->setHeader ( 'Pragma', 'public', true )
                    ->setHeader ( 'Content-type', 'application/force-download' )
                    ->setHeader ( 'Content-Length', filesize($filepath) )
                    ->setHeader ('Content-Disposition', 'attachment' . '; filename=' . basename($filepath) );
        $this->getResponse ()->clearBody ();
        $this->getResponse ()->sendHeaders ();
        readfile ( $filepath );
        exit;
	}

}