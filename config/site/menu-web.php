<?php

return [
    // ================= web menu =================
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
            'icon' => 'fa-tasks',
            'title' => 'Management',

            'name' => '예약 관리',
            'link' => 'javascript:void(0);',
            'route' => null,
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M4' => [
            'icon' => 'fa-bed',
            'title' => null,

            'name' => '객실 관리',
            'link' => 'javascript:void(0);',
            'route' => null,
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M5' => [
            'icon' => 'fa-tools',
            'title' => null,

            'name' => '옵션 관리',
            'link' => null,
            'route' => 'option',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M6' => [
            'icon' => 'fa-tags',
            'title' => null,

            'name' => '할인 관리',
            'link' => null,
            'route' => 'discount',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M7' => [
            'icon' => 'fa-leaf',
            'title' => null,

            'name' => '시즌 관리',
            'link' => null,
            'route' => 'season',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M8' => [
            'icon' => 'fa-chart-line',
            'title' => null,

            'name' => '정산 관리',
            'link' => null,
            'route' => 'calculate',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M9' => [
            'icon' => 'fa-envelope',
            'title' => null,

            'name' => 'SMS 관리',
            'link' => null,
            'route' => 'sms',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M10' => [
            'icon' => 'fa-plane',
            'title' => null,

            'name' => '공휴일 관리',
            'link' => null,
            'route' => 'holiday',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M11' => [
            'icon' => 'fa-gavel',
            'title' => null,

            'name' => '환불규정 관리',
            'link' => null,
            'route' => 'refund-rule',
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],

        'M99' => [
            'icon' => 'fa-user',
            'title' => 'MY PAGE',

            'name' => '마이페이지',
            'link' => 'javascript:void(0);',
            'route' => null,
            'param' => [],
            'continue' => false,
            'dev' => false,
        ],
    ],

    'sub' => [
        'M3' => [
            'S1' => [
                'name' => '예약 현황',
                'link' => null,
                'route' => 'reservation.status',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S2' => [
                'name' => '예약 조회',
                'link' => null,
                'route' => 'reservation.search',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S3' => [
                'name' => '환불 내역',
                'link' => null,
                'route' => 'reservation.refund',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],
        ],

        'M4' => [
            'S1' => [
                'name' => '타입 정보',
                'link' => null,
                'route' => 'type',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S2' => [
                'name' => '객실 막기',
                'link' => null,
                'route' => 'type.block',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],
        ],

        'M99' => [
            'S1' => [
                'name' => '내정보',
                'link' => null,
                'route' => 'mypage',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],

            'S2' => [
                'name' => '비밀번호 변경',
                'link' => null,
                'route' => 'mypage.changePW',
                'param' => [],
                'continue' => false,
                'dev' => false,
            ],
        ],
    ],
];
