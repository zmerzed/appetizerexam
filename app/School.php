<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';

    protected $fillable = [
        'name',
        'division_id'
    ];

    public function Division() {

        return $this->belongsTo(Division::class);
    }

    public function passers() {

        return $this->hasMany(Examinee::class);
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
