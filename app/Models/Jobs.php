<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Jobs extends Model
{
    use HasFactory;
    use LogsActivity;

    
    protected $table       = 'jobs_opening';

    protected $guarded     = ['jobs_id'];
    protected $primaryKey   = 'jobs_id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('jobs_opening master');
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = get_user_data();
        $activity->causer_id = $user->id;
        $activity->causer_type = get_class($user);
    }
}
