<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getSettingLogo')) {
    function getSettingLogo(): ?string
    {
        $setting = DB::table('setting')->first();
        if ($setting && $setting->logo) {
            return 'data:image/png;base64,' . base64_encode($setting->logo);
        }
        return null;
    }
}

if (!function_exists('getSettingWallpaper')) {
    function getSettingWallpaper(): ?string
    {
        $setting = DB::table('setting')->first();
        if ($setting && $setting->wallpaper) {
            return 'data:image/jpeg;base64,' . base64_encode($setting->wallpaper);
        }
        return null;
    }
}
