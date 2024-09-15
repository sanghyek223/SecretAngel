<?php

return [
    // ================= admin menu =================
    'main' => [
        'M1' => [
            'icon' => 'fa-cloud',
            'title' => null,

            'name' => 'Dashboard',
            'link' => null,
            'route' => 'dashboard',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M2' => [
            'icon' => 'fa-bell',
            'title' => 'BULLETIN BOARD',

            'name' => '공지사항',
            'link' => null,
            'route' => 'board',
            'param' => ['code' => 'notice'],
            'continue' => false,
            'dev' => false,
        ],

        'M3' => [
            'icon' => 'fa-users-cog',
            'title' => 'MANAGEMENT',

            'name' => '업체 관리',
            'link' => 'javascript:void(0);',
            'route' => null,
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M4' => [
            'icon' => 'fa-plane',
            'title' => null,

            'name' => '공휴일 설정',
            'link' => null,
            'route' => 'holiday',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M5' => [
            'icon' => 'fa-coffee',
            'title' => null,

            'name' => '편의시설 설정',
            'link' => null,
            'route' => 'amenities',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M6' => [
            'icon' => 'fa-chart-bar',
            'title' => null,

            'name' => '업체분류 설정',
            'link' => null,
            'route' => 'accommodation',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],
    ],

    'sub' => [
        'M3' => [
            'S1' => [
                'name' => '전체 목록',
                'link' => null,
                'route' => 'user',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S2' => [
                'name' => '만료 목록',
                'link' => null,
                'route' => 'user.expired',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S3' => [
                'name' => '삭제 목록',
                'link' => null,
                'route' => 'user.withdrawal',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],
        ],
    ]
];
