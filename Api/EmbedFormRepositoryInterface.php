<?php

namespace Conekta\Payments\Api;

use Conekta\Order;
use Conekta\Payments\Exception\ConektaException;

interface EmbedFormRepositoryInterface
{
    /**
     * @param int $quoteId
     * @param array $orderParams
     * @param float $orderTotal
     * @return Order
     */
    public function generate(int $quoteId, array $orderParams, float $orderTotal): Order;
}
