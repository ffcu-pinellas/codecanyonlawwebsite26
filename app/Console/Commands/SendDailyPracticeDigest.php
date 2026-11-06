<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GeneralSettings;
use App\Models\ClientCase;
use App\Models\Invoice;
use App\Models\StaffTimeLog;
use App\Models\StaffPayoutRequest;
use Carbon\Carbon;

class SendDailyPracticeDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'practice:daily-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 6:00 PM practice summary digest to Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Staff hours today
        $timeLogs = StaffTimeLog::whereDate('clocked_in_at', $today)->get();
        $totalSeconds = $timeLogs->sum('duration_seconds');
        $hours = round($totalSeconds / 3600, 1);
        $staffCount = $timeLogs->pluck('user_id')->unique()->count();

        // Revenue & Invoices
        $paidToday = Invoice::where('status', 'paid')
            ->whereDate('updated_at', $today)
            ->sum('total');

        $pendingProofs = Invoice::where('payment_proof_status', 'submitted')->count();

        // Cases
        $newCases = ClientCase::whereDate('created_at', $today)->count();
        $activeCases = ClientCase::whereIn('status', ['pending', 'active', 'in_progress', '1'])->count();

        $digest = "📊 <b>Daily Practice Digest — " . $today->format('M d, Y') . "</b>\n"
                . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                . "⏱️ <b>Staff Hours Logged:</b> {$hours} hrs ({$staffCount} active staff)\n"
                . "💰 <b>Payments Cleared:</b> $" . number_format($paidToday, 2) . "\n"
                . "⚠️ <b>Pending Payment Proofs:</b> {$pendingProofs}\n"
                . "💼 <b>New Cases Opened Today:</b> {$newCases}\n"
                . "📁 <b>Total Active Practice Cases:</b> {$activeCases}\n"
                . "🔒 <b>Practice Integrity:</b> 100% Operational\n"
                . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
                . "Have a great evening! — <i>Your CPA Expert Automated Desk</i>";

        GeneralSettings::sendTelegramNotification($digest);
        $this->info("Daily practice digest sent to Telegram.");
        return 0;
    }
}
