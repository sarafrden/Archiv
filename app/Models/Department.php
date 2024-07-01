<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'info', 'user_id'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }



public function CompanyDocuments()
{
    return $this->hasMany(CompanyDocument::class);
}

public function DailyReports()
{
    return $this->hasMany(DailyReport::class);
}

public function UnselectedFiles()
{
    return $this->hasMany(UnselectedFile::class);
}

}
