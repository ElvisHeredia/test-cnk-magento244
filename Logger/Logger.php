<?php

namespace Conekta\Payments\Logger;

use Conekta\Payments\Helper\Data;
use Magento\Framework\App\ObjectManager;

class Logger extends \Monolog\Logger
{
    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function addRecord(int $level, string $message, array $context = []): bool
    {
        $objectManager = ObjectManager::getInstance();
        $conektaHelper = $objectManager->create(Data::class);

        if ((int)$conektaHelper->getConfigData('conekta/conekta_global', 'debug')) {
            return parent::addRecord($level, $message, $context);
        }

        return true;
    }
}
