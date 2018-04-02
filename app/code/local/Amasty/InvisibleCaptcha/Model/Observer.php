<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


class Amasty_InvisibleCaptcha_Model_Observer
{
    public function handleControllers($observer)
    {
        /** @var Mage_Core_Controller_Front_Action $action */
        $action = $observer->getControllerAction();
        $request = $action->getRequest();

        if (Mage::getStoreConfig('aminvisiblecaptcha/general/enabledCaptcha')) {
            foreach (Mage::helper('aminvisiblecaptcha')->getCaptchaUrls() as $captchaUrl) {
                if (strpos($request->getRequestUri(), $captchaUrl) !== false) {
                    if ($request->isPost()) {
                        $token = $request->getPost('amasty_invisible_token');
                        $validation = Mage::helper('aminvisiblecaptcha')->verifyCaptcha($token);
                        if (!$validation) {
                            Mage::getSingleton('core/session')->addError(
                                Mage::helper('aminvisiblecaptcha')->__('Something is wrong')
                            );

                            $action
                                ->getResponse()
                                ->setRedirect($this->_getRefererUrl($action))
                                ->sendResponse();

                            Mage::helper('ambase/utils')->_exit();
                        }
                    }
                    break;
                }
            }
        }
    }

    protected function _getRefererUrl($action)
    {
        $refererUrl = $action->getRequest()->getServer('HTTP_REFERER');
        if ($url = $action->getRequest()->getParam(Amasty_InvisibleCaptcha_Helper_Data::PARAM_NAME_REFERER_URL)) {
            $refererUrl = $url;
        }
        if ($url = $action->getRequest()->getParam(Amasty_InvisibleCaptcha_Helper_Data::PARAM_NAME_BASE64_URL)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }
        if ($url = $action->getRequest()->getParam(Amasty_InvisibleCaptcha_Helper_Data::PARAM_NAME_URL_ENCODED)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }

        if (!$this->_isUrlInternal($refererUrl)) {
            $refererUrl = Mage::app()->getStore()->getBaseUrl();
        }
        return $refererUrl;
    }

    protected function _isUrlInternal($url)
    {
        if (false !== strpos($url, 'http')) {
            /**
             * Url must start from base secure or base unsecure url
             */
            if (0 === strpos($url, Mage::app()->getStore()->getBaseUrl())
                || 0 === strpos($url, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true))
            ) {
                return true;
            }
        }
        return false;
    }
}
