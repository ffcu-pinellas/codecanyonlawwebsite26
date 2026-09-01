<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'case_id',
        'client_id',
        'amount',
        'due_date',
        'status',
        'description',
        'late_fee_enabled',
        'late_fee_type',
        'late_fee_is_percentage',
        'late_fee_amount',
        'late_fee_start_date',
        'late_fee_accumulated',
        'payment_info',
        'payment_method',
        'payment_reference',
        'payment_slip_path',
        'payment_notes',
        'payment_submitted_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'late_fee_enabled' => 'boolean',
        'late_fee_is_percentage' => 'boolean',
        'late_fee_amount' => 'decimal:2',
        'late_fee_accumulated' => 'decimal:2',
        'late_fee_start_date' => 'date',
        'payment_submitted_at' => 'datetime',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Compute dynamic late fee, next penalty countdown, and total balance due
     */
    public function getLateFeeDetailsAttribute()
    {
        $baseAmount = floatval($this->amount ?: $this->total_amount ?: 0);
        $isPaid = strtolower($this->status) === 'paid';
        $dynamicLateFee = 0.00;
        $nextPenaltyTime = 0;
        $timeRemainingSec = 0;

        if (!empty($this->late_fee_enabled) && !$isPaid) {
            $startDate = !empty($this->late_fee_start_date) ? strtotime($this->late_fee_start_date) : (!empty($this->due_date) ? strtotime($this->due_date) : 0);
            $now = time();
            $feeRate = !empty($this->late_fee_is_percentage) ? ($baseAmount * (floatval($this->late_fee_amount) / 100)) : floatval($this->late_fee_amount ?: 0);

            if ($startDate > 0 && $now > $startDate) {
                $elapsed = $now - $startDate;
                $feeType = strtolower($this->late_fee_type ?: 'daily');

                if ($feeType === 'hourly') {
                    $intervals = floor($elapsed / 3600);
                    $dynamicLateFee = $intervals * $feeRate;
                    $nextPenaltyTime = $startDate + ($intervals + 1) * 3600;
                } elseif ($feeType === 'weekly') {
                    $intervals = floor($elapsed / (86400 * 7));
                    $dynamicLateFee = $intervals * $feeRate;
                    $nextPenaltyTime = $startDate + ($intervals + 1) * (86400 * 7);
                } elseif ($feeType === 'monthly') {
                    $intervals = floor($elapsed / (86400 * 30));
                    $dynamicLateFee = $intervals * $feeRate;
                    $nextPenaltyTime = $startDate + ($intervals + 1) * (86400 * 30);
                } else { // daily
                    $intervals = floor($elapsed / 86400);
                    $dynamicLateFee = $intervals * $feeRate;
                    $nextPenaltyTime = $startDate + ($intervals + 1) * 86400;
                }
                $timeRemainingSec = max(0, $nextPenaltyTime - $now);
            } elseif ($startDate > 0) {
                $nextPenaltyTime = $startDate;
                $timeRemainingSec = max(0, $startDate - $now);
            }
        }

        $effectiveLateFee = $isPaid ? floatval($this->late_fee_accumulated ?: 0) : max($dynamicLateFee, floatval($this->late_fee_accumulated ?: 0));
        $totalBilled = $baseAmount + $effectiveLateFee;

        return (object) [
            'base_amount' => $baseAmount,
            'late_fee' => $effectiveLateFee,
            'total_billed' => $totalBilled,
            'next_penalty_time' => $nextPenaltyTime,
            'time_remaining_sec' => $timeRemainingSec,
            'is_active' => (!empty($this->late_fee_enabled) && !$isPaid),
            'fee_type' => $this->late_fee_type ?: 'daily',
            'fee_amount' => $this->late_fee_amount ?: 0,
            'is_percentage' => (bool)$this->late_fee_is_percentage,
        ];
    }
}
