<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'type', 'number', 'file_path', 'department_id', 'project_id'
    ];

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }

    public function Project()
    {
        return $this->belongsTo(Project::class);
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
