<?php

namespace App\Models;

use App\Events\SubmissionSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /**
     * Mass assignment.
     * @var string[] $fillable
     */
    protected $fillable = [
        'name',
        'email',
        'message'
    ];

    /**
     * Booted.
     * @return void
     */
    public static function booted(): void
    {
        static::saved(function (Submission $submission) {
            event(new SubmissionSaved($submission));
        });
    }
}
