<?php

namespace Conekta\Payments\Logger;

use Conekta\Payments\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Monolog\DateTimeImmutable;
use Monolog\Logger;

class Logger
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
            return Logger::addRecord($level, $message, $context);
        }

        return true;
    }
}
