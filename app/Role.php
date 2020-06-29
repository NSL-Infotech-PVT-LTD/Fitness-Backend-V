<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model {

    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'category', 'image', 'type','status'];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permission() {
        return $this->belongsToMany(Permission::class)->select('name', 'id');
    }

    /**
     * Grant the given permission to a role.
     *
     * @param  Permission $permission
     *
     * @return mixed
     */
    public function givePermissionTo(Permission $permission) {
        return $this->permissions()->save($permission);
    }

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    protected $appends = array('plans');

    public function getPlansAttribute() {
        try {
            $model = RolePlans::where('role_id', $this->id)->get();
            if ($model->isEmpty() !== true):
                $a = [];
                foreach ($model as $m):
                    $a[$m->fee_type]['id'] = $m->id;
                    $a[$m->fee_type]['fee'] = $m->fee;
                    $a[$m->fee_type]['image'] = $m->image;
                endforeach;
//                dd($a);
                return $a;
            endif;
            return [];
        } catch (Exception $ex) {
            return [];
        }
    }

}
