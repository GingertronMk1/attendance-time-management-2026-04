<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\ShiftStatusEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
#[Appends(['open_shift'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class)->orderByDesc('start_time');
    }

    /**
     * @return Attribute<Shift|false>
     */
    public function openShift(): Attribute
    {
        return Attribute::make(function () {
            return $this->shifts()->whereNull('end_time')->first() ?? false;
        });
    }

    /**
     * @throws \Exception
     */
    public function startShift(): Shift
    {
        $newShift = new Shift([
            'status' => ShiftStatusEnum::PENDING,
            'start_time' => now(),
            'notes' => '',
        ]);
        $newShift->user()->associate($this);
        return $newShift->save() ? $newShift : throw new \Exception('Failed to start shift');
    }
}
