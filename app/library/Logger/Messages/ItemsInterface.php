<?php

namespace Library\Logger\Messages;

interface ItemsInterface
{
    const __ALERT__ = 'ALERT';
    const __CRITICAL__ = 'CRITICAL';
    const __CUSTOM__ = 'CUSTOM';
    const __DEBUG__ = 'DEBUG';
    const __EMERGENCY__ = 'EMERGENCY';
    const __ERROR__ = 'ERROR';
    const __INFO__ = 'INFO';
    const __NOTICE__ = 'NOTICE';
    const __WARNING__ = 'WARNING';
    const __ACCESS__ = 'ACCESS';

    function getItems() : array;
}
