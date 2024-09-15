<?

$code = 'notice';

return [
    'code' => "{$code}", // 게시판 코드
    'skin' => 'notice', // 게시판 스킨
    'name' => '공지사항', // 게시판 명
    'subject' => '제목', // 게시판 Subject 명
    'directory' => "board/{$code}", // 게시판 업로드 경로
    'paginate' => 10, // 페이지별 노출 리스트 수

    'options' => [
        'hide' => ['N' => '공개', 'Y' => '비공개'], // 공개여부
        'notice' => ['Y' => '사용', 'N' => '미사용'], // 상단공지
        'date_type' => ['D' => '하루', 'L' => '장기'], // 기간 타입
        'popup_yn' => ['Y' => '사용', 'N' => '미사용'], // 팝업 사용여부
        'popup_detail' => ['Y' => '설정함', 'N' => '설정안함'], // 팝업 상세보기 링크 사용여부
    ],

    'permission' => [ // 권한 빈값은 전체 접근, 값이있을경우 해당 level 만 접근가능
        'list' => [], // 리스트 권한
        'view' => [], // 상세보기 권한
        'write' => [], // 글쓰기 권한
    ],

    'use' => [ // 사용 유무
        'login' => true, // 로그인 필요
        'link' => false, // 상세링크
        'hide' => true, // 공개옵션
        'date' => false, // 기간설정
        'popup' => false, // 팝업
        'place' => false, // 장소
        'notice' => true, // 공지
        'category' => false, // 카테고리
        'file' => false, // 파일업로드 (단일파일)
        'thumbnail' => false, // 썸네일파일
        'plupload' => true, // 파일업로드 (plupload) 사용
    ],

    'category' => [
        'name' => '카테고리', // 카테고리 명칭
        'type' => 'select', // radio or select
        'item' => [ // 게시판 카테고리 ex) key => value
            '1' => 'c1',
            '2' => 'c2',
        ],
    ],

    'file' => [ // 기본 max 5개 까지 업로드가능 그이상은 DB 추가 필요
        1 => [
            'name' => '파일1',
        ],

        2 => [
            'name' => '파일2',
        ],

        3 => [
            'name' => '파일3',
        ],
//
//        4 => [
//            'name' => '파일4',
//        ],
//
//        5 => [
//            'name' => '파일5',
//        ],
    ],

    'thumbnail' => [
        'name' => '썸네일', // 썸네일 명칭
    ],

    'date' => [
        'name' => '행사일정' // 일정 사용시 일정명
    ],

    'search' => [ // 검색 정보
        'subject' => '제목',
    ],

    'key' => [ // 메뉴 키
        'main' => 'M2',
        'sub' => '',

        'admin_main' => 'M2',
        'admin_sub' => '',
    ],
];
