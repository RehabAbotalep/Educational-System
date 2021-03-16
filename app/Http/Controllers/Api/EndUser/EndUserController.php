<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\EndUserInterface;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    /**
     * @var EndUserInterface
     */
    private $endUserInterface;

    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->endUserInterface = $endUserInterface;
    }

    public function sendComplaint(Request $request)
    {
        return $this->endUserInterface->sendComplaint($request);
    }

    public function schedule(Request $request)
    {
        return $this->endUserInterface->schedule($request);
    }

    public function groupTimeline(Request $request)
    {
        return $this->endUserInterface->groupTimeline($request);
    }

    public function addDiscussion(Request $request)
    {
        return $this->endUserInterface->addDiscussion($request);
    }

    public function allDiscussion(Request $request)
    {
        return $this->endUserInterface->allDiscussion($request);
    }

    public function discussionComment(Request $request)
    {
        return $this->endUserInterface->discussionComment($request);
    }


}
