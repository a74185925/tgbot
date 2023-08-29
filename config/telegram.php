<?php

return [
    'token'        => env('TG_TOKEN', false),
    'url'          => env('TG_URL', false),
    'webhookUrl'   => env('TG_WEB_HOOK_URL', false),
    'webhookPath'  => env('TG_WEB_HOOK_PATH', '/bot/webhook'),
    'support'      => env('TG_SUPPORT', '@'),
    'bot_url'      => env('TG_BOT_URL', ''),
];