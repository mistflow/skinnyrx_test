<?php

namespace App\Jobs;

use App\DTO\SubmissionDTO;
use App\Models\Submission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveSubmission implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public SubmissionDTO $submissionDTO;

    /**
     * Create a new job instance.
     */
    public function __construct(SubmissionDTO $DTO)
    {
        $this->submissionDTO = $DTO;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The task is to save data in database, I will choose to use Eloquent model instead of directly DB::, because Eloquent helps to bind events to create/update
        // It may help later to create logs or to watch what is happening

        $submission = Submission::query()->create($this->submissionDTO->toArray());

        // Fourth subtask requires to use event-listener. Usually in tasks very similar to this one I do use Observers because they are really great fit for tasks like that.
        // Now I will do it as Event-Listener. I will use booted() method to connect Submission::saved() with SubmissionSaved event.
    }
}
