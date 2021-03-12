<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\SubscriptionInterface;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionInterface
     */
    private $subscriptionInterface;

    public function __construct(SubscriptionInterface $subscriptionInterface)
    {
        $this->subscriptionInterface = $subscriptionInterface;
    }

    public function limitSubscriptions()
    {
        return $this->subscriptionInterface->limitSubscriptions();
    }

    public function closedSubscriptions()
    {
        return $this->subscriptionInterface->closedSubscriptions();
    }

}
