<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Services extends Model
{
    use HasFactory;
    use LogsActivity;

    
    protected $table       = 'services';

    protected $guarded     = ['ser_id'];
    protected $primaryKey   = 'ser_id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('services master');
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = get_user_data();
        $activity->causer_id = $user->id;
        $activity->causer_type = get_class($user);
    }
}
