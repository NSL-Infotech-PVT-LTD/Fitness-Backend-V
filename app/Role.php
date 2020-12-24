<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;

class Role extends Model {

    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'category', 'image', 'type', 'status'];

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

    protected $appends = array('plans', 'price_label_monthly', 'price_label_quarterly', 'price_label_half_yearly', 'price_label_yearly');

    public function getPriceLabelMonthlyAttribute() {
        try {
            $model = RolePlans::where('role_id', $this->id)->where('fee_type', 'monthly');
            if ($model->count() != 0):
                return $model->value('fee') . ' AED ' . $this->label;
            endif;
            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function getPriceLabelQuarterlyAttribute() {
        try {
            $model = RolePlans::where('role_id', $this->id)->where('fee_type', 'quarterly');
            if ($model->count() != 0):
                return $model->value('fee') . ' AED ' . $this->label;
            endif;
            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function getPriceLabelHalfYearlyAttribute() {
        try {
            $model = RolePlans::where('role_id', $this->id)->where('fee_type', 'half_yearly');
            if ($model->count() != 0):
                return $model->value('fee') . ' AED ' . $this->label;
            endif;
            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function getPriceLabelYearlyAttribute() {
        try {
            $model = RolePlans::where('role_id', $this->id)->where('fee_type', 'yearly');
            if ($model->count() != 0):
                return $model->value('fee') . ' AED ' . $this->label;
            endif;
            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function getPlansAttribute() {
//        $request = new Request();
//        dd($request->ajax());
        try {
            $model = RolePlans::where('role_id', $this->id)->get();
            if ($model->isEmpty() !== true):
                $a = [];
                foreach ($model as $m):
                    $a[$m->fee_type] = ['id' => $m->id, 'fee' => $m->fee];
                endforeach;
//                dd($a);
                return $a;
            endif;
            return [];
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function PlanDetail() {
        return $this->hasMany(RolePlans::class, 'role_id', 'id')->select('id', 'fee', 'fee_type', 'role_id');
    }

//    public function getCategoryAttribute($value) {
//        return ucfirst(str_replace('_', ' ', $value));
//    }
}
