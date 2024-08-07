<?php

namespace App\Http\Controllers\Api;

use App\DTO\SubmissionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubmitRequest;
use App\Jobs\SaveSubmission;
use Illuminate\Http\Request;

class SubmitController extends BaseController
{
    public function __invoke(SubmitRequest $request)
    {
        $dto = SubmissionDTO::fromArray($request->validated());
        SaveSubmission::dispatch($dto);

        // No response if everything is made correctly
        // If validation failed - SubmitRequest will throw an error

        // But if Job failed - to show error it is quite difficult task
        // There is 2 ways to handle it:

        // All what we could do is save JobID and then make regular requests from client to monitor its status
        // I mean we should create `job_statuses` table, then create middleware LogJobStatus and add it to our Job via middleware() method inside of it.
        // Then we can use our middleware to monitor to update our job status in database and we can do polling like /jobs/ID/status to check is task made or not.

        // The other way is to fire JobFailed event (custom) when Job is failed (try-catch or overriding default Job class to add failed() method).
        // When JobFailed event is fired - we send data about this using WS/Mail to user which connected to API.
    }
}
