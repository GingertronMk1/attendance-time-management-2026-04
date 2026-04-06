<?php

namespace App\Models;

use App\ShiftStatusEnum;
use Database\Factories\ShiftFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['start_time', 'end_time', 'notes', 'status'])]
#[Appends(['length'])]
class Shift extends Model
{
    /** @use HasFactory<ShiftFactory> */
    use HasFactory;

    use HasUuids;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'status' => ShiftStatusEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function length(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->start_time->diff($this->end_time);
            }
        );
    }

    public function stop(): self
    {
        $this->update([
            'end_time' => now(),
        ]);

        return $this;
    }
}
