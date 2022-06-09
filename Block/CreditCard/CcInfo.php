<?php

namespace Conekta\Payments\Block\CreditCard;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\Info;
use Magento\Payment\Model\Config;

class CcInfo extends Info
{
    protected $_template = 'Conekta_Payments::info/creditcard.phtml';

    public function __construct(
        Context $context,
        protected Config $_paymentConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return Phrase|mixed
     * @throws LocalizedException
     */
    public function getCcTypeName()
    {
        $types = $this->_paymentConfig->getCcTypes();
        $ccType = $this->getInfo()->getCcType();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }
        return empty($ccType) ? __('N/A') : $ccType;
    }

    /**
     * @return false|mixed
     * @throws LocalizedException
     */
    public function getAdditionalData()
    {
        $information = $this->getInfo()->getAdditionalInformation();
        if (isset($information['additional_data'])) {
            return $information['additional_data'];
        }

        return false;
    }
}
