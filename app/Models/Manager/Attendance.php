<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Attendance extends Model
{

    protected $table = 'attendances';

    use HasFactory,LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('Attendance')
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
        'punch_time',
        'punch_state',
        'terminal_sn',
        'terminal_alias',
        'area_alias',
        'upload_time',
        'is_mask',
        'employee_id',
        'machine_ip',
        'machine_location',
        'sync_iteration',
        'sync',
        'mark_time',
        'in_out',
        'created_by',
        'updated_by',
    ];

    public function empID()
    {
        return $this->belongsTo(Employee::class, 'employee_code');
    }

}
