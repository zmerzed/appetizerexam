<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\School;

class Examinee extends Model
{
    protected $table = 'examinees';

    protected $fillable = [
        'first_name',
        'last_name',
        'school_id'
    ];

    public function School() {

        return $this->belongsTo(School::class);
    }

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function setCreatedAt($value)
    {
        // Do nothing.
    }
}
