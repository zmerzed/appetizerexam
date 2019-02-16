<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    protected $fillable = [
        'name'
    ];

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function setCreatedAt($value)
    {
        // Do nothing.
    }
}
