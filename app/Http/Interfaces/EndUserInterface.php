<?php


namespace App\Http\Interfaces;


interface EndUserInterface
{
    public function sendComplaint($request);
    public function schedule($request);
    public function groupTimeline($request);
    public function addDiscussion($request);
    public function allDiscussion($request);
    public function discussionComment($request);

}
