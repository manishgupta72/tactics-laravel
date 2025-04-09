<?php

namespace App\Models;


// use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Models\Activity;
// use Spatie\Activitylog\Traits\LogsActivity;

class GeneralSetting extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $table       = 'general_settings';

    protected $guarded     = ['id'];
    protected $primaryKey   = 'id';
    protected static $logName = 'general settings';
    protected static $logFillable = true;
    public $timestamps = false;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logAll()
    //         ->logOnlyDirty()
    //         ->useLogName('general settings');
    // }

    // public function tapActivity(Activity $activity, string $eventName)
    // {
    //     $user = get_user_data();
    //     $activity->causer_id = $user->id;
    //     $activity->causer_type = get_class($user);
    // }
}
