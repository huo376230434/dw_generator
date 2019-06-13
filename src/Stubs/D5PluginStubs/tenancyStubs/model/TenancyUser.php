<?php

namespace App\Model;


use App\Admin\Extensions\BaseExtends\Widgets\BaseBtpModalTrait;
use App\Model\Base\BaseModelTrait;
use App\Notifications\Tenancy\ResetPasswordNotification;
use App\Notifications\Tenancy\VerifyEmail;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TenancyUser extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use Notifiable,AdminBuilder,BaseBtpModalTrait,BaseModelTrait;
    protected $guarded = [];
    //
        
 public function tenancyRoleTenancyUsers()
    {
        return $this->hasMany(TenancyRoleTenancyUser::class);
    }
    
        
 public function tenancyRoles()
    {
        return $this->belongsToMany(TenancyRole::class);
    }
    
        
 public function tenancyUserTenancyPermissions()
    {
        return $this->hasMany(TenancyUserTenancyPermission::class);
    }
    
        
 public function tenancyPermissions()
    {
        return $this->belongsToMany(TenancyPermission::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);

    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));

    }

}
