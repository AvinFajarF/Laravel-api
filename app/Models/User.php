<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Generator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'images'
    ];

    protected $attributes = [
        'role' => 'member'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:8000/password/reset?token=' . $token;
        $this->notify(new ResetPasswordNotification($url, $token));
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    protected $appends = ['created_at_parse','update_at_parse'];


    public function createdAtParse(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat("d F Y"),
        );
    }

    public function updateAtParse(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat("d F Y"),
        );
    }

}
