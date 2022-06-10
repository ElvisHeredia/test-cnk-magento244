<?php

namespace Conekta\Payments\Logger;

use Conekta\Payments\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Monolog\Logger as MonoLogger;

class Logger
{
    /**
     * @param MonoLogger $monolog
     */
    public function __construct(private MonoLogger $monolog)
    {
    }

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
            return $this->monolog->addRecord($level, $message, $context);
        }

        return true;
    }

    /**
     * @param string $string
     * @param array $customerRequest
     * @return void
     */
    public function info(string $string, array $customerRequest): void
    {
        $this->monolog->info($string, $customerRequest);
    }

    /**
     * @param string $string
     * @param array $array
     * @return void
     */
    public function error(string $string, array $array): void
    {
        $this->monolog->error($string, $array);
    }

    /**
     * @param string $message
     * @param array $array
     * @return void
     */
    public function debug(string $message, array $array = []): void
    {
        $this->monolog->debug($message, $array);
    }
}
