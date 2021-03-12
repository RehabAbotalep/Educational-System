<?php


namespace App\Http\Interfaces;


interface SubscriptionInterface
{
    public function limitSubscriptions();

    public function closedSubscriptions();
}
