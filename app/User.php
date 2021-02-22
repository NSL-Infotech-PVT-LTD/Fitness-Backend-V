<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Carbon;

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
        'first_name', 'middle_name', 'last_name', 'child', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'trainer_id', 'trainer_slot', 'address', 'status', 'image', 'parent_id', 'gender', 'city', 'nationality', 'about_us', 'workplace', 'hotel_room_no', 'duration_of_stay', 'check_in', 'check_out', 'my_sessions','payment_params','payment_status'
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
    protected $appends = array('role', 'full_name', 'role_expired_on');

    public function getTrainerSlotAttribute($value) {
        return ($value == null) ? 0 : $value;
    }

    public function getFullNameAttribute() {
        $name = '';
        $name .= $this->first_name;
        $this->middle_name == '' ? '' : $name .= ' ' . $this->middle_name;
        $name .= ' ' . $this->last_name;

        return $name;
    }

    public function getRoleExpiredOnAttribute() {

        try {
            $subscription_endDate = new Carbon\Carbon($this->role->action_date);
            switch ($this->role->current_plan->fee_type):
                case'monthly':
                    $subscription_endDate = $subscription_endDate->addMonth();
                    break;
                case'quarterly':
                    $subscription_endDate = $subscription_endDate->addMonths(3);
                    break;
                case'half_yearly':
                    $subscription_endDate = $subscription_endDate->addMonths(6);
                    break;
                case'yearly':
                    $subscription_endDate = $subscription_endDate->addMonths(12);
                    break;
            endswitch;
//                                dd($subscription_endDate);
//            return $subscription_endDate;
            $subscription_end = new Carbon\Carbon($subscription_endDate);
            return $subscription_end->format('Y-m-d');
        } catch (\Exception $ex) {
            return '';
        }
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
