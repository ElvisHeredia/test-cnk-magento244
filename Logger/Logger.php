<?php

namespace Conekta\Payments\Logger;

use Conekta\Payments\Helper\Data;
use Magento\Framework\App\ObjectManager;

class Logger extends \Monolog\Logger
{
    /**
     * @param $level
     * @param $message
     * @param array $context
     * @return bool
     */
    public function addRecord($level, $message, array $context = []): bool
    {
        $objectManager = ObjectManager::getInstance();
        $conektaHelper = $objectManager->create(Data::class);

        if ((int)$conektaHelper->getConfigData('conekta/conekta_global', 'debug')) {
            return parent::addRecord($level, $message, $context);
        }

        return true;
    }
}
