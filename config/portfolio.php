<?php

return [
    'brand' => 'D',
    'name' => 'Mirza Rusyaidi',
    'first_name' => 'Mirza',
    'last_name' => 'Rusyaidi',
    'title' => 'Software Engineer',
    'headline' => "I build products with a pulse —\ngames, care tools, and the APIs underneath.",
    'tagline' => 'Software engineer in Malaysia. Formerly Dee. I like Laravel, React, and Go — and making serious systems feel a little more alive.',
    'email' => env('PORTFOLIO_EMAIL', 'mirzarusyaidi891@gmail.com'),
    'location' => 'Malaysia',
    'availability' => 'Open to opportunities',
    'presence' => [
        'mode' => env('PORTFOLIO_PRESENCE', 'schedule'),
        'timezone' => env('PORTFOLIO_PRESENCE_TZ', 'Asia/Kuala_Lumpur'),
        'online_from' => env('PORTFOLIO_PRESENCE_FROM', '10:00'),
        'online_until' => env('PORTFOLIO_PRESENCE_UNTIL', '22:00'),
    ],
    'focus' => [
    
    ],
    'meta' => [
        'degree' => 'Software Engineering',
        'discipline' => 'Full Stack Development',
    ],
    'social' => [
        'linkedin' => env('PORTFOLIO_LINKEDIN', 'https://www.linkedin.com/in/mirzarusyaidi19'),
        'github' => env('PORTFOLIO_GITHUB', 'https://github.com/meersyd'),
    ],
    'resume' => env('PORTFOLIO_RESUME', '/resume.pdf'),
    'url' => env('APP_URL', 'http://localhost'),
    'seo' => [
        'title' => 'Mirza Rusyaidi — Software Engineer',
        'description' => 'Portfolio of Mirza Rusyaidi — software engineer in Malaysia building products with a pulse: platforms, APIs, and game-like systems.',
    ],
    'build' => '2026.08',
    'version' => '1.0',
    'stack_label' => 'Laravel · React · Go',
];
