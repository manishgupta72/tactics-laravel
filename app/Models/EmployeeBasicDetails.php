<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmployeeBasicDetails extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table       = 'basic_employee';

    protected $guarded     = ['id'];
    protected $primaryKey  = 'id';


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('employee basic details');
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = get_user_data();
        $activity->causer_id = $user->id;
        $activity->causer_type = get_class($user);
    }


}
