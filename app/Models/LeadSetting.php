<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSetting extends Model
{
    protected $table = 'leads_settings';
    
    protected $fillable = [
        'key_name',
        'value',
        'description'
    ];
}