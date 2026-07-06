<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPenaltyCategory extends Model
{
    use HasFactory;

    protected $table = 'loan_penalties_categories';

    protected $fillable = [

        'name',
        'conditions',
        'descriptions',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

}