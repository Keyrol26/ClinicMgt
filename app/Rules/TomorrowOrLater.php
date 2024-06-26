<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Log;

class TomorrowOrLater implements Rule
{
    public function passes($attribute, $value)
    {

        // $inputDate = Carbon::parse($value);
        // $lastdate = Carbon::now()->addMonth();


        // return $value >= now() && $value <= $lastdate;
        try {
            $inputDate = Carbon::parse($value);
            $tomorrow = Carbon::tomorrow();
            $lastDate = Carbon::now()->addMonth();

            // Log the dates for debugging
            Log::info('Input date: ' . $inputDate->toDateString());
            Log::info('Tomorrow\'s date: ' . $tomorrow->toDateString());
            Log::info('Last date: ' . $lastDate->toDateString());

            // Check if the provided date is between tomorrow and a month from now
            return $inputDate->between($tomorrow, $lastDate);
        } catch (\Exception $e) {
            Log::error('Date parsing error: ' . $e->getMessage());
            return false;
        }

    }

    public function message()
    {
        return 'Please Choose the date between a month from now.';
    }
}
