<?php

namespace Tests\Unit;

use App\DTO\SubmissionDTO;
use App\Http\Controllers\Api\SubmitController;
use App\Http\Requests\Api\SubmitRequest;
use App\Jobs\SaveSubmission;
use App\Models\Submission;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function test_submission_dto()
    {
        // Fake the queue to intercept job dispatches
        Queue::fake();

        // Define your test data
        $submission = new SubmissionDTO(
            name: 'John Doe',
            email: 'john@example.com',
            message: 'This is a test message.'
        );

        $this->assertInstanceOf(SubmissionDTO::class, $submission);
    }

    /**
     * @return void
     */
    public function test_job_dispatching_and_processing()
    {
        // Fake the queue to intercept job dispatches
        Queue::fake();

        // Define your test data
        $submissionDTO = new SubmissionDTO(
            name: 'John Doe',
            email: 'john@example.com',
            message: 'This is a test message.'
        );

        // Dispatch the job
        SaveSubmission::dispatch($submissionDTO);

        // Assert that the job was pushed onto the queue
        Queue::assertPushed(SaveSubmission::class, function ($job) use ($submissionDTO) {
            return $job->submissionDTO === $submissionDTO;
        });

        // Optionally, test the job processing
        Queue::assertPushed(SaveSubmission::class, function ($job) {
            $job->handle();  // Directly call the handle method to simulate processing
            return true;
        });
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
