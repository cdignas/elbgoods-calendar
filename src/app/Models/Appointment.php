<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Calendar appointment object.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class Appointment extends Model
{
    /**
     * Array of fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * Get appointments in date range and without overlapping.
     *
     * @param Builder $query
     * @param string  $start
     * @param string  $end
     *
     * @return Builder
     */
    public function scopeInDateRangeWithoutOverlapping(
        Builder $query, string $start, string $end
    ): Builder {
        return $query->where(
            function ($dateQuery) use ($start, $end) {
                $dateQuery->where(
                    function ($q) use ($start) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>', $start);
                    }
                );
                $dateQuery->orWhere(
                    function ($q) use ($start, $end) {
                        $q->where('start_date', '>=', $start)
                            ->where('end_date', '<=', $end);
                    }
                );
                $dateQuery->orWhere(
                    function ($q) use ($end) {
                        $q->where('start_date', '<', $end)
                            ->where('end_date', '>=', $end);
                    }
                );
            }
        );
    }
}
