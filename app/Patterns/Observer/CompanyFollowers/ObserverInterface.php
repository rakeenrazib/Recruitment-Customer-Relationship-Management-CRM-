<?php

namespace App\Patterns\Observer\CompanyFollowers;

interface ObserverInterface
{
    /**
     * Observer-pattern notification method.
     * the Subject calls notify()
     * on every registered observer when the Subject state changes.
     */
    public function notify(): void;
}
