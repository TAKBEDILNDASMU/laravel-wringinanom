<?php

namespace App\Http\Services;

use App\Models\BirthNote;
use App\Models\DeathNote;
use App\Models\LetterBusiness;
use App\Models\LetterCounter;
use App\Models\LetterDeath;
use App\Models\LetterFuel;
use App\Models\LetterIncapacity;
use App\Models\LetterLost;

class LetterCounterService
{
    public function getNextLetterNumber($year = null): int
    {
        $currentYear = $year ?? now()->year;
        $counter = LetterCounter::firstOrCreate(['year' => $currentYear], ['latest_no' => 0]);
        $counter->increment('latest_no');
        return $counter->latest_no;
    }

    public function resetRecentLetterNumber($year = null)
    {
        $currentYear = $year ?? now()->year;

        // Get the highest `no_letter` from all letter tables for the given year
        $maxNoLetter = max(
            LetterBusiness::whereYear('created_at', $currentYear)->max('no_letter') ?? 0,
            LetterDeath::whereYear('created_at', $currentYear)->max('no_letter') ?? 0,
            LetterIncapacity::whereYear('created_at', $currentYear)->max('no_letter') ?? 0,
            LetterLost::whereYear('created_at', $currentYear)->max('no_letter') ?? 0,
            LetterFuel::whereYear('created_at', $currentYear)->max('no_letter') ?? 0
        );

        // Update the `latest_no` in the `letter_counters` table
        $counter = LetterCounter::firstOrCreate(['year' => $currentYear], ['latest_no' => 0]);
        $counter->latest_no = $maxNoLetter;
        $counter->save();
    }

    private function getNextNoteNumber($modelClass, $year = null)
    {
        $currentYear = now()->year;

        // Check whether any record exist in database
        $latestRecord = $modelClass::where('year', $currentYear)
            ->orderByDesc('no_dok_journey')
            ->first();

        if ($latestRecord) {
            // If record exist, return next year
            return $latestRecord->no_dok_journey + 1;
        } else {
            // If no record, return 1             
            return 1;
        }
    }

    public function getNextBirthLetterNumber($year = null)
    {
        return $this->getNextNoteNumber(BirthNote::class, $year);
    }

    public function getNextDeathLetterNumber($year = null)
    {
        return $this->getNextNoteNumber(DeathNote::class, $year);
    }
}
