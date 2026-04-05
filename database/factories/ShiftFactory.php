<?php

namespace Database\Factories;

use App\Models\Shift;
use App\Models\User;
use App\ShiftStatusEnum;
use DateInterval;
use DateMalformedIntervalStringException;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

/**
 * @extends Factory<Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws DateMalformedIntervalStringException
     */
    public function definition(): array
    {
        $user = User::query()->count() < 3
            ? User::factory()->create()
            : User::query()->inRandomOrder()->first();

        $start = Date::createFromDate(fake()->dateTimeBetween('-1 week'));
        $end = $start->addMinutes(fake()->numberBetween(1, 4800));

        return [
            'user_id' => $user->id,
            'start_time' => $start,
            'end_time' => $end,
            'notes' => fake()->paragraph(),
            'status' => fake()->randomElement(ShiftStatusEnum::cases())->value,
        ];
    }
}
