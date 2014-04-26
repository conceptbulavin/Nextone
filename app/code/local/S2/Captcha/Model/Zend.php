<?php
class S2_Captcha_Model_Observer extends Mage_Captcha_Model_Zend
{
    public function isRequired($login = null)
    {
        // if ($this->_isUserAuth() || !$this->_isEnabled() || !in_array($this->_formId, $this->_getTargetForms())) {
        //     return false;
        // }

        return ($this->_isShowAlways() || $this->_isOverLimitAttempts($login)
            || $this->getSession()->getData($this->_getFormIdKey('show_captcha'))
        );
    }
}
