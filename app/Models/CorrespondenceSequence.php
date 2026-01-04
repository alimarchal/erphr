<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrespondenceSequence extends Model
{
    protected $fillable = [
        'type',
        'year',
        'division_id',
        'prefix',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'last_number' => 'integer',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public static function getNextNumber(string $type, int $year, ?string $divisionId = null): int
    {
        $sequence = static::firstOrCreate(
            [
                'type' => $type,
                'year' => $year,
                'division_id' => $divisionId,
            ],
            [
                'prefix' => $type === 'Receipt' ? 'RR' : 'DR',
                'last_number' => 0,
            ]
        );

        $sequence->increment('last_number');

        return $sequence->last_number;
    }

    public static function generateRegisterNumber(string $type, int $year, ?string $divisionId = null, ?string $divisionCode = null): string
    {
        $number = static::getNextNumber($type, $year, $divisionId);
        $prefix = $type === 'Receipt' ? 'RR' : 'DR';

        if ($divisionCode) {
            return sprintf('%s/%d/%s/%05d', $prefix, $year, $divisionCode, $number);
        }

        return sprintf('%s/%d/%05d', $prefix, $year, $number);
    }
}
