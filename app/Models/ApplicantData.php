<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantData extends Model
{

    protected $table        = 'applicant_data';
    protected $primaryKey   = 'ap_id';
    public $timestamps      = false;

}