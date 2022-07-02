<?php

namespace Library\Logger;

use Phalcon\Logger\Adapter\AdapterInterface;
use Library\Logger\Messages\ItemsInterface;

class Logger
{
    private AdapterInterface $adapter;
    
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function log(ItemsInterface $messageCreator) : void
    {
        $items = $messageCreator->getItems();
        $this->saveItems($items);
    }

    private function saveItems(array $items) : void
    {
        $this->adapter->begin();
        foreach ($items as $item) {
            $this->adapter->process($item);
        }
        $this->adapter->commit();
    }
}
