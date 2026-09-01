<?php

namespace App\Services;

use App\Models\User;

class ChatwootService
{
    /**
     * Get settings from settings.json or defaults.
     */
    public static function getSettings(): array
    {
        $data = [];

        // 1. Prioritize Database (Permanent & Immune to Git resets)
        try {
            $generalSettings = \App\Models\GeneralSettings::first();
            if ($generalSettings && !empty($generalSettings->chat_settings)) {
                $dbData = is_array($generalSettings->chat_settings) ? $generalSettings->chat_settings : json_decode($generalSettings->chat_settings, true);
                if (!empty($dbData) && is_array($dbData)) {
                    $data = $dbData;
                }
            }
        } catch (\Throwable $th) {}

        // 2. Fallback to settings.json
        if (empty($data)) {
            $settingsPath = storage_path('settings.json');
            if (file_exists($settingsPath)) {
                $json = json_decode(file_get_contents($settingsPath), true);
                $data = $json['chat'] ?? [];
            }
        }

        return [
            'provider' => $data['provider'] ?? 'chatwoot',
            'website_token' => $data['website_token'] ?? '',
            'base_url' => rtrim($data['base_url'] ?? 'https://app.chatwoot.com', '/'),
            'account_id' => $data['account_id'] ?? '',
            'hmac_key' => $data['hmac_key'] ?? '',
            'tawkto_property_id' => $data['tawkto_property_id'] ?? '',
        ];
    }

    /**
     * Generate secure HMAC token for a user to ensure continuous chat retention across devices.
     */
    public static function getUserIdentity(?User $user): ?array
    {
        if (!$user) {
            return null;
        }

        $settings = self::getSettings();
        $identifier = 'client_' . $user->id;
        $hmacKey = $settings['hmac_key'];

        $identifierHash = !empty($hmacKey) ? hash_hmac('sha256', $identifier, $hmacKey) : null;

        $avatarUrl = $user->profile_photo_url ?? null;
        if ($avatarUrl && !str_starts_with($avatarUrl, 'http')) {
            $avatarUrl = url($avatarUrl);
        }

        return [
            'identifier' => $identifier,
            'identifier_hash' => $identifierHash,
            'name' => $user->name ?: 'Client #' . $user->id,
            'email' => $user->email,
            'avatar_url' => $avatarUrl,
            'phone_number' => $user->phone ?? null,
            'custom_attributes' => [
                'client_id' => $user->id,
                'portal_role' => $user->roles->first()?->name ?? 'client',
                'account_type' => 'Legal & CPA Client',
            ],
        ];
    }
}
