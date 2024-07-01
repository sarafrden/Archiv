<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnselectedFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'date', 'type', 'number', 'file_path', 'department_id'
    ];

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
}
