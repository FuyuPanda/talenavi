<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
     protected $fillable = [
        'title',
        'assignee',
        'dueDate',
        'timeTracked',
        'status',
        'priority'
    ];
}
