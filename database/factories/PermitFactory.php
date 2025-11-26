<?php

namespace Database\Factories;

use App\Models\Permit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermitFactory extends Factory
{
    protected $model = Permit::class;

    public function definition()
    {
        // Random start date within past year to 1 year ahead
        $start = $this->faker->dateTimeBetween('-1 year', '+1 year');
        $durations = [1, 3, 7, 30, 90, 180, 365];
        $duration = $this->faker->randomElement($durations);

        return [
            'licence_plate' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{3}'), // Example: AB12CDE
            'valid_from' => $start,
            'valid_to' => (clone $start)->modify("+{$duration} days"),
        ];
    }

    // generates a permit where the current date (now) is between valid_from and valid_to
    // valid_from is set to a date between 1 year ago and now
    // valid_to is valid_from plus a random duration (1, 3, 7, 30, 90, 180, or 365 days)
    public function currentlyValid()
    {
        return $this->state(function () {
            $durations = [1, 3, 7, 30, 90, 180, 365];
            $duration = $this->faker->randomElement($durations);
            $start = $this->faker->dateTimeBetween('-1 year', 'now');
            return [
                'licence_plate' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{3}'),
                'valid_from' => $start,
                'valid_to' => (clone $start)->modify("+{$duration} days"),
            ];
        });
    }

    // generates a permit that has already expired
    // valid_to is set to a date between 1 year ago and now (so it’s in the past)
    // valid_from is before valid_to by a random duration
    public function expired()
    {
        return $this->state(function () {
            $durations = [1, 3, 7, 30, 90, 180, 365];
            $duration = $this->faker->randomElement($durations);
            $end = $this->faker->dateTimeBetween('-1 year', 'now');
            $start = (clone $end)->modify("-{$duration} days");
            return [
                'licence_plate' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{3}'),
                'valid_from' => $start,
                'valid_to' => $end,
            ];
        });
    }

    // generates a permit that starts in the future
    // valid_from is set to a date between now and 1 year ahead
    // valid_to is valid_from plus a random duration
    public function future()
    {
        return $this->state(function () {
            $durations = [1, 3, 7, 30, 90, 180, 365];
            $duration = $this->faker->randomElement($durations);
            $start = $this->faker->dateTimeBetween('now', '+1 year');
            return [
                'licence_plate' => $this->faker->regexify('[A-Z]{2}[0-9]{2}[A-Z]{3}'),
                'valid_from' => $start,
                'valid_to' => (clone $start)->modify("+{$duration} days"),
            ];
        });
    }
}
