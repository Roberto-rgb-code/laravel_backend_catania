<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5174', 'http://localhost:5173', 'https://catania.com.mx/', 'https://adminpanelcatania.netlify.app', 'https://admin-panel-catania-ver.vercel.app/'], // Añade 5175
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
