<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'info', 'department_id'
    ];

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }


    public function DailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function scopeDepartmentRestricted(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user->type !== 'admin') {
            return $query->where('department_id', $user->department_id);
        }

        return $query;
    }
}
