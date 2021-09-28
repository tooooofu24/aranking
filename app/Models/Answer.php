<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $dates = ['updated_at'];
    protected $fillable = [
        'line_id',
        'line_name',
        'q10',
        'q11',
        'q12',
        'q13',
        'q14',
        'q15',
        'q16',
        'q17',
        'q18',
        'q19',
        'q20',
        'q21',
        'q22',
        'q23',
        'q24',
        'q25',
        'q26',
        'q27',
        'q28',
        'q29',
        'q30',
        'q31',
        'q32',
        'q33',
        'q34',
        'q35',
        'q36',
        'q37',
        'q38',
        'q39',
        'q40',
    ];
}
