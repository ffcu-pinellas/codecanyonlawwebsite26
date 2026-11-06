<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        "site_name", "site_tag_line", "site_sub_tag_line", "author_name",
        "company_phone", "company_email", "company_address", "whatsapp_number",
        "telegram_reply_enabled",
        "og_meta_title", "og_meta_description", "og_meta_image",
        "chat_settings", "payment_settings", "kyc_settings"
    ];

    protected $casts = [
        'chat_settings' => 'array',
        'payment_settings' => 'array',
        'kyc_settings' => 'array',
        'telegram_reply_enabled' => 'boolean',
    ];

    /**
     * Dynamic Contact Helpers with environment fallbacks
     */
    public static function getCompanyPhone()
    {
        $setting = static::first();
        return ($setting && !empty($setting->company_phone)) 
            ? $setting->company_phone 
            : env('COMPANY_PHONE', '(216) 230-1837');
    }

    public static function getCompanyEmail()
    {
        $setting = static::first();
        return ($setting && !empty($setting->company_email)) 
            ? $setting->company_email 
            : env('COMPANY_EMAIL', 'support@yourcpaexpert.com');
    }

    public static function getCompanyAddress()
    {
        $setting = static::first();
        return ($setting && !empty($setting->company_address)) 
            ? $setting->company_address 
            : env('COMPANY_ADDRESS', '582 Professional Way, Financial District, DC');
    }

    public static function getWhatsappNumber()
    {
        $setting = static::first();
        return ($setting && !empty($setting->whatsapp_number)) 
            ? $setting->whatsapp_number 
            : env('WHATSAPP_NUMBER', '+12162301837');
    }

    /**
     * Dispatch Telegram notification with optional interactive inline keyboard
     * Returns decoded response array with message_id for reply mapping
     */
    public static function sendTelegramNotification($message, $replyMarkup = null)
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $chatId = config('services.telegram.chat_id') ?: env('TELEGRAM_CHAT_ID');

        if (empty($botToken) || empty($chatId)) {
            return null;
        }

        try {
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ];

            if (!empty($replyMarkup)) {
                $payload['reply_markup'] = is_array($replyMarkup) ? json_encode($replyMarkup) : $replyMarkup;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                return json_decode($response, true);
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return null;
    }
}
