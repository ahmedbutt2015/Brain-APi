<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'twilio_auth_sid', 'twilio_auth_token', 'twilio_phone_number', 'password',
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

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    public function routeNotificationForTwilio()
    {
        return [
            'twilio_auth_sid' => $this->twilio_auth_sid,
            'twilio_auth_token' => $this->twilio_auth_token,
            'twilio_phone_number' => $this->twilio_phone_number
        ];
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
