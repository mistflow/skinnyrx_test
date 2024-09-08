1. API Endpoint
   - There is an API endpoint that works with accepts/json header and gives json response
   - All routes in api.php are forced to give JSON response because of ForceJsonResponse middleware.
   - API endpoint is bound to SubmitController(invoke method) and SubmitRequest (validation)
   - Data is validated and if something is wrong (the only rule is 'required') - error is thrown

2. Database setup
   - Migration is created as in requirements

3. Job Queue
    - Controller dispatches Job
    - I decided to use DTO to transfer data inside SaveSubmission Job
    - As driver for queues I selected predis/predis

4. Events
    - After Submission::class model saved() method is triggered (it is triggered each time model is created, updated) - SubmissionSaved Event is fired
    - This logic is described inside Submission::booted() static method.
    - We place Submission model instance inside this Event and then letting Listener get it as public prop
    - Inside Listener very simple Log::info() is made with details of Submission and text

5. Error handling
    - a. Invalid data handling is working great because of SubmitRequest (form request) - it throws errors automatically if request data is not as in requirements
    - b. Errors during Job processing are hard to provide, because Jobs are async and finishes when response is already formed. I have 2 ways to do it, but it is not what could be done in 2-3 hours test task:
      - Jobs migration & fetch status by ID:
        - While creating job in controller - remember its ID and return it before Job is done
        - On client side give user possibility to check what is with Job by its ID (or do it automatically via polling)
      - WebSockets or other Event-based way to notify:
        - Bind all or only specific Jobs to FailedJob Event and notify user via WS/Mail that Job has failed

6. Documentation
    - a. To install the project:
      - Install composer, php (8.2), redis-server
      - Create MySQL database and fill .env with data as in documentation
        - Important note: be careful with REDIS_CLIENT and redis in general, use predis
      - use `php artisan migrate` to run migrations
      - run `php artisan test` to run tests
      - run `php artisan serve` for preview, for real setup `Nginx` with php-fpm should be used
    - b. To test API endpoint you may use `curl` or `Postman`, I will provide example of `curl` requests and important notes about what to specify in `Postman` UI
      - Successful request: `curl -X POST http://localhost:8000/api/submit      -H "Content-Type: application/json"      -d '{"name": "John Doe", "email": "john@example.com", "message": "Hello, this is a test message."}'`
      - Failed validation request: `curl -X POST http://localhost:8000/api/submit      -H "Content-Type: application/json"      -d '{"name": "John Doe", "email": "john@example.com"}'`
      - DO NOT forget to specify:
        - Content-Type: application/json
        - body of request
        - check port and URL in general twice

7. Simple Unit Test
    - Made very simple unit test which checks if $submission is instance of SubmissionDTO
    - I tried to do UnitTest which checks Queues
