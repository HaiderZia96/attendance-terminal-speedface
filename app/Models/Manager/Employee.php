<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory,LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('Employee')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'employee_code',
        'student_reg_no',

        'name',
        'image',
        'designation',
        'department',
        'campus',

        'role_status',

        'st_in_day1',
        'st_in_day2',
        'st_in_day3',
        'st_in_day4',
        'st_in_day5',
        'st_in_day6',
        'st_in_day7',

        'st_out_day1',
        'st_out_day2',
        'st_out_day3',
        'st_out_day4',
        'st_out_day5',
        'st_out_day6',
        'st_out_day7',

        'created_by',
        'updated_by',
    ];
    public function attendance()
    {
        return $this->hasMany(Employee::class, 'employee_code');
    }
}
