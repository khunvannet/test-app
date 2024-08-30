<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'projectId',
        'testcase',
        'active'
    ];

    protected $table = 'testruns';
     protected $casts = [
        'testCases' => 'array', 
    ];

    // If you have a Project model
    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }
}