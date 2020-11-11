<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'child', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'address', 'status', 'image', 'parent_id', 'gender', 'city','nationality','about_us'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = array('role', 'full_name');

    public function getFullNameAttribute() {
        $name = '';
        $name .= $this->first_name;
        $this->middle_name == '' ? '' : $name .= ' ' . $this->middle_name;
        $name .= ' ' . $this->last_name;

        return $name;
    }

    public function getRoleAttribute() {
        try {
            $currentUserRole = \DB::table('role_user')->where('user_id', $this->id);
            $rolesID = $currentUserRole->pluck('role_id');
            if ($rolesID->isEmpty() !== true):
                $role = Role::whereIn('id', $rolesID);
                if ($role->get()->isEmpty() !== true):
                    $data = $role->select('name', 'id', 'image', 'category')->with('permission')->first();
                    return (object) array_merge($data->toArray(), ['current_plan' => RolePlans::select('id', 'fee_type', 'fee')->whereId($currentUserRole->first()->role_plan_id)->first(), 'action_date' => $currentUserRole->first()->created_at]);
                endif;
            endif;
            return (object) [];
        } catch (\Exception $ex) {
            return (object) [];
        }
    }

    public static function usersIdByPermissionName($name) {

        $permissions = \App\Permission::where('name', 'like', '%' . $name . '%')->get();
        if ($permissions->isEmpty())
            return [];
        $role = \DB::table('permission_role')->where('permission_id', $permissions->first()->id)->get();
        if ($role->isEmpty())
            return [];
        return \DB::table('role_user')->whereIN('role_id', $role->pluck('role_id'))->pluck('user_id')->toArray();
    }

}
