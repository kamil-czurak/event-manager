<?php

namespace common\services;

use Psr\Log\LoggerInterface;

class OptimizerChain extends \Spatie\ImageOptimizer\OptimizerChain
{

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

}