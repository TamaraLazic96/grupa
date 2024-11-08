<?php

namespace App\Utils;

class AdminMenuConstants
{
    public const ADMIN_MENU_HOME = 'Home';
    public const ADMIN_MENU_ABOUT = 'About';

    public const ADMIN_MENU = [
        'home' => self::ADMIN_MENU_HOME,
        'about' => self::ADMIN_MENU_ABOUT,
    ];
}