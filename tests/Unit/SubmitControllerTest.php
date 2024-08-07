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
use PHPUnit\Framework\TestCase;

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

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
