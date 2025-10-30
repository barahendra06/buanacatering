<?php
//file : app/constants.php

define('COMPLIANCE_STATUS_YES', 1);
define('COMPLIANCE_STATUS_NO', 2);
define('COMPLIANCE_STATUS_NOT_ASSESSED', 3);

define('ADMIN',  'administrator');

//comment
define('COMMENT_PREMIUM_LIKE_AMOUNT', 10);

//age
define('MINIMUM_AGE', 12);
define('MAXIMUM_AGE', 21);

//message
define('TAKEN_MESSAGE_LIMIT', 7);

//conversation notification
define('CONVERSATION_NOTIFICATION_LIMIT', 6);

//notification notification
define('NOTIFICATION_LIMIT', 6);

//cache
define('DB_QUERY_CACHE_PERIOD_FAST', 1);  //in minutes
define('DB_QUERY_CACHE_PERIOD_MEDIUM', 15);  //in minutes
define('DB_QUERY_CACHE_PERIOD_SLOW', 60);  //in minutes


//status
define('STATUS_APPROVED',  1);
define('STATUS_REJECTED',  2);
define('STATUS_PENDING',  3);
define('STATUS_INVITED',  4);

//activity type
define('ACTIVITY_SHARE',  1);
define('ACTIVITY_REGISTER',  2);
define('ACTIVITY_COMMENT',  3);
define('ACTIVITY_POST',  4);
define('ACTIVITY_PHOTO',  5);
define('ACTIVITY_INFOGRAFIS',  6);
define('ACTIVITY_POLL',  7);
define('ACTIVITY_INVITE_MEMBER',  8);
define('ACTIVITY_REFERENCE_POLLING',  9);
define('ACTIVITY_REFERRED_POLLING',  9);
define('ACTIVITY_PREMIUM',  10);
define('ACTIVITY_WINNER',  11);
define('ACTIVITY_REGISTER_FROM_INVITATION',  12);
define('ACTIVITY_PHOTO_APPROVED',  13);
define('ACTIVITY_PHOTO_SUBMITED',  14);
define('ACTIVITY_GRAPHIC_APPROVED',  15);
define('ACTIVITY_GRAPHIC_SUBMITED',  16);
define('ACTIVITY_POST_APPROVED',  17);
define('ACTIVITY_POST_SUBMITED',  18);
define('ACTIVITY_PREMIUM_COMMENT',  19);
define('ACTIVITY_OTHER',  20);
define('ACTIVITY_REPLY_COMMENT', 21);
define('ACTIVITY_LIKE_COMMENT', 22);
define('ACTIVITY_APPROVE_EVENT_PARTICIPANT', 23);
define('ACTIVITY_REJECT_EVENT_PARTICIPANT', 39);
define('ACTIVITY_PENDING_EVENT_PARTICIPANT', 40);
define('ACTIVITY_APPROVE_EVENT_PARTICIPANT_TEAM', 24);
define('ACTIVITY_REJECT_EVENT_PARTICIPANT_TEAM', 41);
define('ACTIVITY_PENDING_EVENT_PARTICIPANT_TEAM', 42);
define('ACTIVITY_INVITE_MEMBER_TO_JOIN_TEAM', 38);
define('ACTIVITY_ZETCON_VOTING', 25);
define('ACTIVITY_ARTICLE_APPROVED', 26);
define('ACTIVITY_ARTICLE_SUBMITED', 27);
define('ACTIVITY_ARTICLE_REJECTED', 33);
define('ACTIVITY_ARTICLE_SPAMMED', 34);
define('ACTIVITY_NEW_BADGE', 28);
define('ACTIVITY_SUBMIT_WEEKLY_CHALLENGE', 29);
define('ACTIVITY_SUBMIT_NATIONAL_CHALLENGE', 32);
define('ACTIVITY_REDEEM_POINT', 30);
define('ACTIVITY_REDEMPTION_APPROVED', 44);
define('ACTIVITY_REDEMPTION_REJECTED', 45);
define('ACTIVITY_PHOTO_REJECTED',  35);
define('ACTIVITY_GRAPHIC_REJECTED',  36);
define('ACTIVITY_POST_REJECTED',  37);

//POLL QUESTION TYPE
define('POLL_QUESTION_MULTIPLE_CLOSE',  1);
define('POLL_QUESTION_MULTIPLE_OPEN',  2);
define('POLL_QUESTION_TEXT',  3);

// ROLE
define('ROLE_MEMBER', 1);
define('ROLE_ADMIN', 4);
define('ROLE_EDITOR', 6);
define('ROLE_CONTRIBUTOR', 9);
define('ROLE_FINANCE', 10);
define('ROLE_OPERATIONAL', 11);
define('ROLE_MANAGER', 12);
define('ROLE_COACH', 13);
define('ROLE_INVESTOR', 14);
define('ROLE_RENTER', 15);
define('ROLE_INVESTOR_FINANCE', 16);
define('ROLE_SUPER_ADMIN', 99);
define('ROLE_HEAD_COACH', 17);
define('ROLE_FREELANCE_COACH', 18);

//EDUCATION TYPE
define('EDUCATION_SD', 1);
define('EDUCATION_SMP', 2);
define('EDUCATION_SMA', 3);
define('EDUCATION_KULIAH', 4);

const EDUCATION_TYPE_ARRAY = [1=>'SMP',2=>'SMA',3=>'KULIAH'];

define('SETTING_BANNER_ANNOUNCEMENT_IMAGE', 14);
define('SETTING_BANNER_ANNOUNCEMENT_LINK', 15);
define('SETTING_BANNER_ANNOUNCEMENT_IMAGE2', 25);
define('SETTING_BANNER_ANNOUNCEMENT_LINK2', 26);
define('SETTING_BANNER_ANNOUNCEMENT_IMAGE3', 27);
define('SETTING_BANNER_ANNOUNCEMENT_LINK3', 28);

define('SETTING_BANNER_ANNOUNCEMENT_MOBILE', 18);
define('SETTING_BANNER_ANNOUNCEMENT_MOBILE2', 29);
define('SETTING_BANNER_ANNOUNCEMENT_MOBILE3', 30);

define('SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE', 16);
define('SETTING_SIDEBAR_ANNOUNCEMENT_LINK', 17);

define('SETTING_POPUP', 20);
define('SETTING_DASHBOARD_BANNER_ANNOUNCEMENT1_IMAGE', 21);
define('SETTING_DASHBOARD_BANNER_ANNOUNCEMENT1_LINK', 22);
define('SETTING_DASHBOARD_BANNER_ANNOUNCEMENT2_IMAGE', 23);
define('SETTING_DASHBOARD_BANNER_ANNOUNCEMENT2_LINK', 24);

// ADS
//HOME PAGE
define('SETTING_HEADER_IMAGE', 25);
define('SETTING_HEADER_LINK', 33);

define('SETTING_HOMEPAGE_TOP_IMAGE', 26);
define('SETTING_HOMEPAGE_TOP_LINK', 34);

define('SETTING_HOMEPAGE_MIDDLE_IMAGE', 27);
define('SETTING_HOMEPAGE_MIDDLE_LINK', 35);

define('SETTING_HOMEPAGE_BOTTOM_IMAGE', 41);
define('SETTING_HOMEPAGE_BOTTOM_LINK', 42);


//SIDEBAR
define('SETTING_SIDEBAR_TOP_IMAGE', 29);
define('SETTING_SIDEBAR_TOP_LINK', 37);

define('SETTING_SIDEBAR_BOTTOM_IMAGE1', 30);
define('SETTING_SIDEBAR_BOTTOM_LINK1', 38);

define('SETTING_SIDEBAR_BOTTOM_IMAGE2', 31);
define('SETTING_SIDEBAR_BOTTOM_LINK2', 39);

define('SETTING_SIDEBAR_BOTTOM_IMAGE3', 32);
define('SETTING_SIDEBAR_BOTTOM_LINK3', 40);

// AUTHENTICATION PAGE
define('SETTING_AUTHENTICATION_PAGE_IMAGE', 28);
define('SETTING_AUTHENTICATION_PAGE_LINK', 36);

//IMAGE SIZE
define('IMAGE_TINY', 'tiny');
define('IMAGE_TINY_WIDTH', 100);
define('IMAGE_TINY_HEIGHT', 100);

define('IMAGE_SMALL', 'small');
define('IMAGE_SMALL_WIDTH', 262);
define('IMAGE_SMALL_HEIGHT', 130);

define('IMAGE_MEDIUM', 'medium');
define('IMAGE_MEDIUM_WIDTH', 408);
define('IMAGE_MEDIUM_HEIGHT', 304);

define('IMAGE_LARGE', 'large');
define('IMAGE_LARGE_WIDTH', 750);
define('IMAGE_LARGE_HEIGHT', 400);

define('IMAGE_EXTRA_LARGE', 'extra-large');
define('IMAGE_EXTRA_LARGE_WIDTH', 1200);
define('IMAGE_EXTRA_LARGE_HEIGHT', 800);


define('PHOTO_CATEGORY_NATIONAL_CHALLENGE', 1);

define('POST_MODEL_TYPE_1', 1);
define('POST_MODEL_TYPE_2', 2);

define('CHALLENGE_TYPE_WEEKLY', 1);
define('CHALLENGE_TYPE_NATIONAL', 2);

define('REDEMPTION_DAY', 1);

const NOTIFICATION_WITHOUT_SUBJECT = [ACTIVITY_PREMIUM_COMMENT,ACTIVITY_ARTICLE_SUBMITED,ACTIVITY_NEW_BADGE,ACTIVITY_ARTICLE_APPROVED];


const REDEEM_PRIZE_ICON = [
								1=>'fa-comments',
								2=>'fa-mobile-phone',
								3=>'fa-mobile-phone',
								4=>'fa-mobile-phone',
								5=>'fa-file-text',
								6=>'fa-mobile-phone'
						  ];

define('POST_PAGES', 100);

define('PARENTS_TYPE_FATHER', 1);
define('PARENTS_TYPE_MOTHER', 2);
define('PARENTS_TYPE_OTHER', 3);

define('GUARDIAN_STATUS_FATHER', 1);
define('GUARDIAN_STATUS_MOTHER', 2);
define('GUARDIAN_STATUS_GUARDIAN', 3);

define('STAGE_TYPE_HOOPS_KIDS', 1);
define('STAGE_TYPE_HOOPS', 2);
define('STAGE_TYPE_ROOKIE', 3);
define('STAGE_TYPE_STARTER', 4);
define('STAGE_TYPE_PRE_HOOPS', 5);
define('STAGE_TYPE_ELITE', 6);

define('COURSE_TYPE_WEEKDAY', 1);
define('COURSE_TYPE_WEEKEND', 2);
define('COURSE_TYPE_PRIVATE', 3);
define('COURSE_TYPE_PHYSICAL_DEVELOPMENT', 7);
define('COURSE_TYPE_ELITE', 8);
define('COURSE_TYPE_REGULAR', 4);


define('BRANCH_GRAHAPENA', 1);
define('BRANCH_PAKUWON', 2);
define('BRANCH_JOGJA', 3);
define('BRANCH_PAKUWON_CITY', 4);

define('SUBSCRIPTION_ACTIVE', 1);
define('SUBSCRIPTION_INACTIVE', 2);
define('SUBSCRIPTION_PAST_DUE', 3);
define('SUBSCRIPTION_TRIAL', 4);
define('SUBSCRIPTION_SCHEDULED', 5);

define('PAYMENT_PENDING_CONFIRMATION', 1);
define('PAYMENT_CONFIRMED', 2);
define('PAYMENT_FAILED', 3);
define('PAYMENT_EXPIRED', 3);

//payment type
define('PAYMENT_CASH', 1);
define('PAYMENT_TRANSFER', 2);
define('PAYMENT_DEBIT', 3);
define('PAYMENT_KREDIT', 4);
define('PAYMENT_ACCOUNT_BALANCE', 99);

//account balance type
define('ACCOUNT_BALANCE_PAYMENT_RETURNED', 1);
define('ACCOUNT_BALANCE_PAYMENT_EXCEED', 2);
define('ACCOUNT_BALANCE_PAYMENT', 3);


//ledger accounts
define('LEDGER_ACCOUNT_CASH', 101);
define('LEDGER_ACCOUNT_BANK', 102);
define('LEDGER_ACCOUNT_RECEIVABLE', 103);  	//piutang
define('LEDGER_ACCOUNT_PAYABLE', 201);		//utang
define('LEDGER_ACCOUNT_UNEARNED_REVENUE', 202);		//pendapatan diterima di muka
define('LEDGER_ACCOUNT_INVOICE_PAYABLE', 203);		
define('LEDGER_ACCOUNT_DISCOUNT', 301);
define('LEDGER_ACCOUNT_OPERATIONAL_COST', 302);
define('LEDGER_ACCOUNT_REGISTRATION_REVENUE', 401);
define('LEDGER_ACCOUNT_TUITION_REVENUE', 402);
define('LEDGER_ACCOUNT_OTHER_REVENUE', 403);
define('LEDGER_ACCOUNT_CANCELLATION_REVENUE', 404);
define('LEDGER_ACCOUNT_APPAREL_REVENUE', 405);
define('LEDGER_ACCOUNT_FIELD_RENT_REVENUE', 406);

//ledger accounts type
define('LEDGER_ACCOUNT_TYPE_ACTIVA', 1);
define('LEDGER_ACCOUNT_TYPE_PASSIVA', 2);
define('LEDGER_ACCOUNT_TYPE_COST', 3);
define('LEDGER_ACCOUNT_TYPE_REVENUE', 4);

// apparel type
define('APPAREL_JERSEY', 1);
define('APPAREL_GYM_BAG', 2);
define('APPAREL_SOCKS', 3);
define('APPAREL_BALLS', 4);

// post type
define('POST_TYPE_BERITA', 1);
define('POST_TYPE_CERITA', 2);
define('POST_TYPE_AGENDA', 3);
define('POST_TYPE_INFORMATION', 4);

//invoice item type
define('INVOICE_ITEM_TYPE_REGISTRATION', 1);
define('INVOICE_ITEM_TYPE_SUBSCRIPTION', 2);
define('INVOICE_ITEM_TYPE_GOODS', 3);
define('INVOICE_ITEM_TYPE_TRIAL', 4);
define('INVOICE_ITEM_TYPE_COURT_ORDER', 5);
define('INVOICE_ITEM_TYPE_APPAREL', 6);

const INVOICE_ITEM_TYPE_MIDTRANS_ACCEPTABLE_ARR = [
    INVOICE_ITEM_TYPE_REGISTRATION,
    INVOICE_ITEM_TYPE_SUBSCRIPTION,
    INVOICE_ITEM_TYPE_TRIAL,
    INVOICE_ITEM_TYPE_COURT_ORDER,
    INVOICE_ITEM_TYPE_APPAREL
];

//revenue status
define('REVENUE_STATUS_UNRECOGNIZED', 1);
define('REVENUE_STATUS_RECOGNIZED', 2);
define('REVENUE_STATUS_REFUNDED', 3);
define('REVENUE_STATUS_CANCELLED', 4);
define('REVENUE_STATUS_ON_LEAVE', 5);

//student report trimester
define('STUDENT_REPORT_TRIMESTER_1', 1);
define('STUDENT_REPORT_TRIMESTER_2', 2);
define('STUDENT_REPORT_TRIMESTER_3', 3);

//student report semester
define('STUDENT_REPORT_SEMESTER_1', 1);
define('STUDENT_REPORT_SEMESTER_2', 2);

//student report period
define('STUDENT_REPORT_PERIOD_1', 1);
define('STUDENT_REPORT_PERIOD_2', 2);

//classroom status
define('CLASSROOM_ACTIVE', 1);
define('CLASSROOM_INACTIVE', 2);

// special program status
define('SPECIAL_PROGRAM_ACTIVE', 1);
define('SPECIAL_PROGRAM_INACTIVE', 2);

define('POST_CATEGORY_UMUM', 99);

// Sales
define('SALES_ACTIVE', 1);

// BMI Scale
define('BMI_SCALE_MIN_VALUE', 0);
define('BMI_SCALE_MAX_VALUE', 999);
define('BMI_SCALE_MAX_Y_GRAPH_VALUE', 45);
define('BMI_SCALE_ADDED_GRAPH_VALUE', 2); 
define('BMI_SCALE_STATUS_VERY_UNDERWEIGHT', 1);
define('BMI_SCALE_STATUS_UNDERWEIGHT', 2);
define('BMI_SCALE_STATUS_NORMAL', 3);
define('BMI_SCALE_STATUS_OVERWEIGHT', 4);
define('BMI_SCALE_STATUS_OBESE', 5);

// Level
define('LEVEL_BEGINNER', 1);
define('LEVEL_INTERMEDIATE', 2);
define('LEVEL_ADVANCE', 3);
define('LEVEL_ELITE', 4);
define('LEVEL_PRO_ELITE', 5);

const LEVELS_ELITE = [4,5];

// Basketball Curriculum
define('BASKETBALL_CURRICULUM_ACTIVE', 1);
define('BASKETBALL_CURRICULUM_INACTIVE', 0);

// Character Curriculum
define('CHARACTER_CURRICULUM_ACTIVE', 1);
define('CHARACTER_CURRICULUM_INACTIVE', 0);

// product
define('PRODUCT_TYPE_REGISTRATION', 1);
define('PRODUCT_TYPE_SUBSCRIPTION', 2);
define('PRODUCT_TYPE_GOODS', 3);
define('PRODUCT_TYPE_APPAREL', 4);
define('PRODUCT_TYPE_COURT', 5);

define('PRODUCT_JERSEY', 4);
define('PRODUCT_GYM_BAG', 5);
define('PRODUCT_SOCKS', 6);
define('PRODUCT_COURT', 7);

const REPORT_TRIMESTER_CONST = [
	1 => 'Trimester 1',
	2 => 'Trimester 2',
	3 => 'Trimester 3'
];

const REPORT_SEMSETER_CONST = [
    1 => 'Semester 1',
    2 => 'Semester 2',
];

const REPORT_PERIOD_CONST = [
	1 => 'Period 1',
	2 => 'Period 2',
];

const DAYS_ARR_CONST = [
	0 => 'Sunday',
	1 => 'Monday',
	2 => 'Tuesday',
	3 => 'Wednesday',
	4 => 'Thursday',
	5 => 'Friday',
	6 => 'Saturday'
];

const MIGRATION_POST_CATEGORY_MAP = [
								1=>99,
								3=>99,
								4=>99,
								5=>99,
								7=>100,
								11=>100,
								12=>3,
								13=>2
						  ];


//dennis 2018-01-16
const MONTHS_ID_ARR_CONST = [
					1=>'januari',
					2=>'februari',
					3=>'maret',
					4=>'april',
					5=>'mei',
					6=>'juni',
					7=>'juli',
					8=>'agustus',
					9=>'september',
					10=>'oktober',
					11=>'november',
					12=>'desember'
				];

const DAY_ID_ARR_CONST = [
    0 => 'minggu', 
    1 => 'senin', 
    2 => 'selasa', 
    3 => 'rabu', 
    4 => 'kamis', 
    5 => 'jumat', 
    6 => 'sabtu'
];

// STUDENT ASSESSMENT REPORT CONST
const PRE_HOOPS_ASSESSMENT_CONST = [
	1 => 'KEEP ON TRYING',
	2 => 'KEEP WORKING ON IT',
	3 => 'YOU ARE DOING A GOOD JOB',
	4 => 'YOU ARE GETTING BETTER EVERY DAY',
	5 => 'YOU ARE REALLY IMPROVING',
];

const ASSESSMENT_CONST = [
	1 => 'KEEP PRACTICE',
	2 => 'NICE WORK',
	3 => 'GOING GREAT',
	4 => 'EXCELLENT',
	5 => 'ALL STAR',
];

const SUBSCRIPTION_STATUS_TO_REVENUE_STATUS = [
    SUBSCRIPTION_ACTIVE => [REVENUE_STATUS_RECOGNIZED, REVENUE_STATUS_UNRECOGNIZED],
    SUBSCRIPTION_INACTIVE => [REVENUE_STATUS_CANCELLED, REVENUE_STATUS_REFUNDED],
];

// END OF STUDENT ASSESSMENT REPORT CONST

// student report type
define('STUDENT_REPORT_TYPE', 1);
define('STUDENT_PLACEMENT_TEST_REPORT_TYPE', 2);
define('STUDENT_REPORT_WEEKLY_TYPE', 3);
define('STUDENT_REPORT_PHYSICAL_DEVELOPMENT_TYPE', 4);

// placement test result value
define('LEVEL_BEGINNER_RESULT', 5.4);
define('LEVEL_INTERMEDIATE_RESULT', 8.4);

// PLACEMENT TEST CONST
const PLACEMENT_ASSESSMENT_TEST_CONST = [
	1 => 'KEEP WORKING',
	2 => 'GETTING BETTER',
	3 => 'NICE WORK',
	4 => 'GOING GREAT',
	5 => 'ALL STAR',
];

// Physical Development Test
const PHYSICAL_DEVELOPMENT_MEASUREMENTS = [
    'beep_test' => [
        'text' => "Beep Test",
        'input_type' => "number",
        'placeholder' => 'Beep Test (Lap)',
        'category' => 'endurance',
    ],
    'run_1200m_boys' => [
        'text' => "Run 1200m Boys",
        'input_type' => "number",
        'placeholder' => 'Type (min)',
        'category' => 'endurance',
    ],
    'run_1000m_girls' => [
        'text' => "Run 1000m Girls",
        'input_type' => "number",
        'placeholder' => 'Type (min)',
        'category' => 'endurance',
    ],
    'sprint_20m' => [
        'text' => "Sprint 20m",
        'input_type' => "number",
        'placeholder' => 'Type (second)',
        'category' => 'speed',
    ],
    'sprint_60m' => [
        'text' => "Sprint 60m",
        'input_type' => "number",
        'placeholder' => 'Type (second)',
        'category' => 'speed',
    ],
    'sit_and_reach' => [
        'text' => "Sit & Reach",
        'input_type' => "number",
        'placeholder' => 'Type (cm)',
        'category' => 'flexibility'
    ],
    't_drill_test' => [
        'text' => '"T" Drill Test',
        'input_type' => "number",
        'placeholder' => 'Type (second)',
        'category' => 'agility'
    ],
    'push_up_amrap' => [
        'text' => "Push up AMRAP",
        'input_type' => "number",
        'placeholder' => 'Type (times)'
    ],
    'modified_push_up_amrap' => [
        'text' => "Modified Push up AMRAP",
        'input_type' => "number",
        'placeholder' => 'Type (times)'
    ],
    'sit_up_30second' => [
        'text' => "Sit up 30second",
        'input_type' => "number",
        'placeholder' => 'Type (times)'
    ],
    'sit_up_60second' => [
        'text' => "Sit up 60second",
        'input_type' => "number",
        'placeholder' => 'Type (times)'
    ],
    'vertical_jump' => [
        'text' => "Vertical Jump",
        'input_type' => "number",
        'placeholder' => 'Type (cm)'
    ],
    'pull_up' => [
        'text' => "Pull Up",
        'input_type' => "number",
        'placeholder' => 'Type (times)'
    ],
];

// Age key -> score -> category
const BEEP_TEST_CATEGORY = [
    "male" => [
        10 => [
            0 => "Very Poor",
            9 => "Poor",
            21 => "Fair",
            29 => "Average",
            38 => "Good",
            48 => "Very Good",
            63 => "Excellent",
        ],
        11 => [
            0 => "Very Poor",
            9 => "Poor",
            22 => "Fair",
            31 => "Average",
            41 => "Good",
            52 => "Very Good",
            68 => "Excellent",
        ],
        12 => [
            0 => "Very Poor",
            9 => "Poor",
            24 => "Fair",
            34 => "Average",
            46 => "Good",
            58 => "Very Good",
            76 => "Excellent",
        ],
        13 => [
            0 => "Very Poor",
            11 => "Poor",
            26 => "Fair",
            39 => "Average",
            51 => "Good",
            65 => "Very Good",
            85 => "Excellent",
        ],
        14 => [
            0 => "Very Poor",
            13 => "Poor",
            29 => "Fair",
            43 => "Average",
            56 => "Good",
            71 => "Very Good",
            93 => "Excellent",
        ],
        15 => [
            0 => "Very Poor",
            14 => "Poor",
            31 => "Fair",
            45 => "Average",
            59 => "Good",
            75 => "Very Good",
            98 => "Excellent",
        ],
        16 => [
            0 => "Very Poor",
            15 => "Poor",
            33 => "Fair",
            48 => "Average",
            62 => "Good",
            79 => "Very Good",
            103 => "Excellent",
        ],
        17 => [
            0 => "Very Poor",
            16 => "Poor",
            35 => "Fair",
            50 => "Average",
            65 => "Good",
            82 => "Very Good",
            108 => "Excellent",
        ],
        18 => [
            0 => "Very Poor",
            34 => "Poor",
            53 => "Fair",
            67 => "Average",
            85 => "Good",
            100 => "Very Good",
            129=> "Excellent",
        ],
    ],
    "female" =>  [
        10 => [
            0 => "Very Poor",
            7 => "Poor",
            17 => "Fair",
            24 => "Average",
            31 => "Good",
            39 => "Very Good",
            50 => "Excellent",
        ],
        11 => [
            0 => "Very Poor",
            6 => "Poor",
            16 => "Fair",
            24 => "Average",
            32 => "Good",
            41 => "Very Good",
            49 => "Excellent",
        ],
        12 => [
            0 => "Very Poor",
            5 => "Poor",
            16 => "Fair",
            25 => "Average",
            33 => "Good",
            42 => "Very Good",
            55 => "Excellent",
        ],
        13 => [
            0 => "Very Poor",
            5 => "Poor",
            17 => "Fair",
            25 => "Average",
            34 => "Good",
            43 => "Very Good",
            57 => "Excellent",
        ],
        14 => [
            0 => "Very Poor",
            5 => "Poor",
            17 => "Fair",
            25 => "Average",
            34 => "Good",
            44 => "Very Good",
            58 => "Excellent",
        ],
        15 => [
            0 => "Very Poor",
            5 => "Poor",
            17 => "Fair",
            26 => "Average",
            35 => "Good",
            45 => "Very Good",
            59 => "Excellent",
        ],
        16 => [
            0 => "Very Poor",
            5 => "Poor",
            17 => "Fair",
            26 => "Average",
            35 => "Good",
            45 => "Very Good",
            60 => "Excellent",
        ],
        17 => [
            0 => "Very Poor",
            5 => "Poor",
            17 => "Fair",
            26 => "Average",
            36 => "Good",
            46 => "Very Good",
            61 => "Excellent",
        ],
        18 => [
            0 => "Very Poor",
            34 => "Poor",
            53 => "Fair",
            67 => "Average",
            85 => "Good",
            100 => "Very Good",
            129=> "Excellent",
        ],
    ],
];

// Sit Reach category (cm) : Gender -> category
const SIT_REACH_CATEGORY = [
    'male' => [
        0 => 'Poor',
        4 => 'Fair',
        7 => 'Average',
        11 => 'Good',
        14 => "Excellent"
    ],
    'female' => [
        0 => 'Poor',
        4 => 'Fair',
        7 => 'Average',
        12 => 'Good',
        15 => "Excellent"
    ]
];

// T Test category (cm) : Gender -> category
const T_TEST_CATEGORY = [
    'male' => [
        '0' => 'Excellent',
        '9.5' => 'Good',
        '10.51' => 'Average',
        '11.51' => 'Poor',
    ],
    'female' => [
        '0' => 'Excellent',
        '10.5' => 'Good',
        '11.51' => 'Average',
        '12.51' => 'Poor',
    ]
];

// Push Up AMRAP Boys and Girls : score -> category
const PUSH_UP_CATEGORY = [
    0 => 'Poor',
    20 => 'Fair',
    35 => 'Average',
    45 => 'Good',
    55 => 'Excellent',
];

const MODIFIED_PUSH_UP_CATEGORY = [
    0 => 'Poor',
    6 => 'Fair',
    17 => 'Average',
    34 => 'Good',
    49 => 'Excellent',
];

const SIT_UP_30 = [
    'male' => [
        0 => 'Poor',
        17 => 'Fair',
        20 => 'Average',
        26 => 'Good',
        31 => 'Excellent',
    ],
    'female' => [
        0 => 'Poor',
        9 => 'Fair',
        15 => 'Average',
        21 => 'Good',
        26 => 'Excellent',
    ]
];

const SIT_UP_60 = [
    'male' => [
        0 => 1,
        10 => 2,
        21 => 3,
        30 => 4,
        41 => 5,
    ],
    'female' => [
        0 => 1,
        3 => 2,
        10 => 3,
        20 => 4,
        29 => 5,
    ]
];

const VERTICAL_JUMP = [
    'male' => [
        '0' => 'Poor',
        '30' => 'Fair',
        '40' => 'Average',
        '50' => 'Good',
        '65,01' => 'Excellent',
    ],
    'female' => [
        '0' => 'Poor',
        '26' => 'Fair',
        '36' => 'Average',
        '47' => 'Good',
        '58.01' => 'Excellent',
    ]
];

const SPRINT_60M = [
    'male' => [
        '0' => 5,
        '7.3' => 4,
        '8.4' => 3,
        '9.7' => 2,
        '11.1' => 1,
    ],
    'female' => [
        '0' => 5,
        '8.5' => 4,
        '9.9' => 3,
        '11.5' => 2,
        '13.4' => 1,
    ]
];

const RUN_1200M_CATEGORY = [
    '0' => 5,
    '3.14' => 4,
    '4.26' => 3,
    '5.13' => 2,
    '6.34' => 1,
];

const RUN_1000M_CATEGORY = [
    '0' => 5,
    '3.53' => 4,
    '4.57' => 3,
    '5.59' => 2,
    '7.24' => 1,
];

const PULL_UP_CATEGORY = [
    'male' => [
        0 => 1,
        5 => 2,
        9 => 3,
        14 => 4,
        19 => 5,
    ],
    'female' => [
        0 => 1,
        3 => 2,
        10 => 3,
        22 => 4,
        40 => 5,
    ]
];

const PIVOT_PHYSICAL_DEVELOPMENT_MEASUREMENT = [
    'beep_test' => BEEP_TEST_CATEGORY,
    'sit_and_reach' => SIT_REACH_CATEGORY,
    't_drill_test' => T_TEST_CATEGORY,
    'push_up_amrap' => PUSH_UP_CATEGORY,
    'modified_push_up_amrap' => MODIFIED_PUSH_UP_CATEGORY,
    'sit_up_30second' => SIT_UP_30,
    'sit_up_60second' => SIT_UP_60,
    'vertical_jump' => VERTICAL_JUMP,
    'sprint_60m' => SPRINT_60M,
    'pull_up' => PULL_UP_CATEGORY,
    'run_1200m_boys' => RUN_1200M_CATEGORY,
    'run_1000m_girls' => RUN_1000M_CATEGORY,
];

// Student Status
define('STUDENT_STATUS_ACTIVE', 1);
define('STUDENT_STATUS_CUTI', 2);
define('STUDENT_STATUS_QUIT', 3);
define('STUDENT_STATUS_TRIAL', 4);
define('STUDENT_STATUS_INACTIVE', 3);


// Trial Limit
define('MAX_TRIAL', 2);

//attendance status related constant
define('ATTENDANCE_NORMAL', 1);
define('ATTENDANCE_OUT_OF_SCHEDULE', 2);

// price plan
define('PRICE_PLAN_FREE_TRIAL', 29);

// NEWS
define('MAX_ROW_NEWS', 10);
define('MAX_ROW_NEWS_HEADLINE', 5);

// POST CATEGORY FILTER
define('POST_CATEGORY_FILTER_FEATURES', 1);
define('POST_CATEGORY_FILTER_COLUMN', 2);
define('POST_CATEGORY_FILTER_NEWS', 3);

define('DEFAULT_API_ERROR_MESSAGE', 'Terjadi gangguan sistem. Silahkan kontak admin.');

const LEVELS_SHORTNAME = [
    'Beginner' => 'B',
    'Intermediate' => 'I',
    'Advance' => 'A',
    'Elite' => 'E',
    'Pro Elite' => 'PE',
];

const MONTHS_ARR = [
    1=>'january',
    2=>'february',
    3=>'march',
    4=>'april',
    5=>'may',
    6=>'june',
    7=>'july',
    8=>'august',
    9=>'september',
    10=>'october',
    11=>'november',
    12=>'december'
];

define('MIN_PLAYER_REGISTRATION', 6);
define('MIN_OFFICIAL_REGISTRATION', 1);
define('MAX_OFFICIAL_TOTAL', 2);

define('MAX_ORDER_NUMBER_LENGTH',  9);
define('INVOICE_ITEM_TYPE_REGISTRATION_TOURNAMENT_FEE', 7);
define('INVOICE_ITEM_TYPE_REGISTRATION_TOURNAMENT_PACKAGE', 8);
define('PAYMENT_TYPE_TRANSFER', 2);
define('PAYMENT_TYPE_MIDTRANS', 101);

define('CURRENCY_USD', 2);

define('PAYMENT_CATEGORY_REGISTRATION_TOURNAMENT', 8);
define('PAYMENT_CATEGORY_RE_REGISTRATION', 2);
define('PAYMENT_CATEGORY_NEW_STUDENT', 1);

define('COMPETITION_CATEGORY_BASKET', 12);

// phase type
define('PHASE_TYPE_GROUP_STAGE', 1);
define('PHASE_TYPE_KNOCKOUT_STAGE', 2);

define('PHASE_GROUP_STAGE', 1);
define('PHASE_PLAYOFF', 2);
define('PHASE_SWEET16', 3);
define('PHASE_BIG8', 4);
define('PHASE_FANTASTIC4', 5);
define('PHASE_SEMIFINAL', 6);
define('PHASE_FINAL', 7);

const PHASE_GROUP_STAGE_ARR = [1,8];

//is championship
define('REGULAR_COMPETITION',  0);
define('CHAMPIONSHIP_COMPETITION',  1);

define('TEAM_ROLE_PLAYER', 1);
define('TEAM_ROLE_COACH', 2);
define('TEAM_ROLE_ASSISTANT_COACH', 3);
define('TEAM_ROLE_OFFICIAL', 4);
define('TEAM_ROLE_COMPANION_TEACHER', 5);
define('TEAM_ROLE_MEDICAL_SUPPORT', 6);
define('TEAM_ROLE_DANCER', 7);
define('TEAM_ROLE_3X3_PLAYER', 8);
define('TEAM_ROLE_WRITER', 9);
define('TEAM_ROLE_PHOTOGRAPHER', 10);
define('TEAM_ROLE_CONTENT_CREATOR', 11);
define('TEAM_ROLE_3X3_PLAYER_P', 17);
define('TEAM_ROLE_QUEST_PARTICIPANT', 18);
define('TEAM_ROLE_COMPANION_PARENT', 19);

define('TEAM_ROLE_TYPE_OFFICIAL', 2);
define('TEAM_ROLE_TYPE_PLAYER', 1);


const BASKETBALL_PLAYER = [
    TEAM_ROLE_PLAYER,
    TEAM_ROLE_3X3_PLAYER,
    TEAM_ROLE_3X3_PLAYER_P
];

define('MATCH_OFFICIAL_TYPE_COORDINATOR', 1);
define('MATCH_OFFICIAL_TYPE_SUPERVISOR', 2);
define('MATCH_OFFICIAL_TYPE_REFEREE', 3);
define('MATCH_OFFICIAL_TYPE_TABLE_LOGGER', 4);

define('MATCH_STATUS_FULLTIME', 11);

const EXTENSION_IMAGE_ARR = ['jpg','jpeg','png'];


define('PAYMENT_METHOD_TRANSFER', 1);
define('PAYMENT_METHOD_GOPAY', 11);
define('PAYMENT_METHOD_SHOPEEPAY', 12);
define('PAYMENT_METHOD_VA_BCA', 15);
define('PAYMENT_METHOD_VA_BNI', 16);
define('PAYMENT_METHOD_VA_BANK_LAIN', 17);
define('PAYMENT_METHOD_VA_BRI', 18);
define('PAYMENT_METHOD_CREDIT_CARD', 19);
define('PAYMENT_METHOD_VA_PERMATA', 20);
define('PAYMENT_METHOD_VA_MANDIRI', 21);
define('PAYMENT_METHOD_TUNAI', 22);

define('PAYMENT_STATUS_WAITING_CONFIRMATION', 1);
define('PAYMENT_STATUS_PAID', 2);
define('PAYMENT_STATUS_EXPIRED', 3);
define('PAYMENT_STATUS_RETURNED_TO_BALANCE', 4);
define('PAYMENT_STATUS_UNPAID', 5);

const PAYMENT_METHODS = [
    [
        'value' => "bca-va",
        "img" => 'img/payment-logo/bca_new.png',
        'text' => "BCA",
        'payment_method_id' => PAYMENT_METHOD_VA_BCA
    ],
    [
        'value' => "mandiri-va",
        "img" => 'img/payment-logo/mandiri_new.png',
        'text' => "MANDIRI",
        'payment_method_id' => PAYMENT_METHOD_VA_MANDIRI
    ],
    [
        'value' => "bni-va",
        "img" => 'img/payment-logo/bni_new.png',
        'text' => "BNI",
        'payment_method_id' => PAYMENT_METHOD_VA_BNI
    ],
    [
        'value' => "bri-va",
        "img" => 'img/payment-logo/bri_new.png',
        'text' => "BRI",
        'payment_method_id' => PAYMENT_METHOD_VA_BRI
    ],
    [
        'value' => "permata-va",
        "img" => 'img/payment-logo/permata_new.png',
        'text' => "PERMATA",
        'payment_method_id' => PAYMENT_METHOD_VA_PERMATA
    ],
    [
        'value' => 'other-va',
        'img' => null,
        'text' => 'Other Bank',
        'payment_method_id' => PAYMENT_METHOD_VA_BANK_LAIN
    ],
    [
        'value' => "credit-card",
        "img" => 'img/payment-logo/credit-card.png',
        'text' => "Credit Card",
        'payment_method_id' => PAYMENT_METHOD_CREDIT_CARD
    ],
    [
        'value' => "shopeepay",
        "img" => 'img/payment-logo/gopay.png',
        'text' => "ShopeePay",
        'payment_method_id' => PAYMENT_METHOD_SHOPEEPAY
    ],
    [
        'value' => "gopay",
        "img" => 'img/payment-logo/shopeepay.png',
        'text' => "GoPay",
        'payment_method_id' => PAYMENT_METHOD_GOPAY
    ],
];

define('MAX_ROW_PHOTO_ALBUM', 10);
define('MAX_ROW_PHOTO_ALBUM_FEATURED', 3);
define('MAX_ROW_PROMO', 10);

const MONTH_TRIMESTER_ARR = [
    '1' => ['01-01','02-01','03-01','04-01'],
    '2' => ['05-01','06-01','07-01','08-01'],
    '3' => ['09-01','10-01','11-01','12-01'],
];

define('CLASSROOM_LOG_STATUS_CREATED', 1);
define('CLASSROOM_LOG_STATUS_EDITED', 2);
define('CLASSROOM_LOG_STATUS_DELETED', 3);


define('GATE_API_BASE_URL', 'http://103.75.55.66:8098/');
define('GATE_API_LIST_SCHEDULE', 'accLevel/list');
define('GATE_API_DETAIL_SCHEDULE_BY_ID', 'accLevel/getById');
define('GATE_API_DETAIL_SCHEDULE_BY_NAME', 'accLevel/getByName');

define('GATE_ASSIGN_STUDENT_SCHEDULE', 'accLevel/syncPerson');
define('GATE_DELETE_STUDENT_SCHEDULE', 'accLevel/deleteLevel');

define('LEVEL_ID_ON_LEAVE', '402881e48dae0854018db0ec979603a3');

// define('GATE_PAGE_NO', 1);
// define('GATE_PAGE_SIZE', 300);

define('CLASSROOM_REPLACEMENT_TYPE_TEMPORARY', 1);
define('CLASSROOM_REPLACEMENT_TYPE_PERMANENT', 2);
define('CLASSROOM_REPLACEMENT_TYPE_ON_LEAVE', 3);

define('CLASSROOM_REPLACEMENT_STATUS_PENDING', 1);
define('CLASSROOM_REPLACEMENT_STATUS_APPROVED', 2);
define('CLASSROOM_REPLACEMENT_STATUS_REJECTED', 3);
define('CLASSROOM_REPLACEMENT_STATUS_CANCELLED', 4);

const GATE_DEVICE_NAME_SCAN_IN = ['IN 1', 'IN 2'];
const GATE_DEVICE_NAME_SCAN_OUT = ['OUT 1', 'OUT 2'];

define('ATTENDANCE_STATUS_ATTENDED', 1);
define('ATTENDANCE_STATUS_SCHEDULED', 2);
define('ATTENDANCE_STATUS_ABSENT', 3);
define('ATTENDANCE_STATUS_ALL', 4);

define('MAX_ROW_HISTORY', 5);


// Arka - 080724 (NOTIFICATION FEATURE API & WEB)
// notification type
const NOTIFICATION_TYPE_ARRAY = [
    'Inbox'=>1,
    'Team'=>2,
    'Student Of The Month' => 8
];

// push notification type
const PUSH_NOTIFICATION_TYPE_ARRAY = [
    'Inbox'=>1,
    'News'=>2,
    // 'Video'=>3,
    'Match Detail'=>4,
    'Promo'=>5,
    'Ticket'=>6,
    'Announcement'=>7,
    'External Url'=>8,
    'Mission List'=>9,
    'Achievement List'=>10,
    // 'Comic'=>11,
    'Deep Link'=>12,
    'Schedule Reminder' => 13,
    'Student Of The Month' => 14,
    'Request Action' => 15,
];

// push notification target
const PUSH_NOTIFICATION_TARGET_ARRAY = [
    1 => 'All',
    // 2 => 'Province',
    3 => 'City',
    // 4 => 'School',
    5 => 'Branch',
    6 => 'Parents/Students',
];

define('PUSH_NOTIFICATION_TO_ALL', 1);
define('PUSH_NOTIFICATION_TO_PROVINCE', 2);
define('PUSH_NOTIFICATION_TO_CITY', 3);
define('PUSH_NOTIFICATION_TO_SCHOOL', 4);
define('PUSH_NOTIFICATION_TO_BRANCH', 5);
define('PUSH_NOTIFICATION_TO_PARENTS', 6);

//firebase topic dev. 1 = enable, 0 = disable
define('ENABLE_TOPIC_DEV', 1);

// notification sender
define('ADMIN_SENDER_NOTIFICATION',  1);

// Inbox Template
define('NOTIFICATION_TEMPLATE_INVOICE_CREATED', 1);
define('NOTIFICATION_TEMPLATE_SOTM', 2);

// Push Notification Template
define('PUSH_NOTIFICATION_TEMPLATE_STUDENT_SCHEDULE_REMINDER', 1);
define('PUSH_NOTIFICATION_TEMPLATE_SOTM', 2);
define('PUSH_NOTIFICATION_TEMPLATE_INVOICE_CREATED', 3);
define('PUSH_NOTIFICATION_TEMPLATE_STUDENT_BIRTHDAY', 4);
define('PUSH_NOTIFICATION_TEMPLATE_STUDENT_RESCHEDULE_REMINDER', 5);
define('PUSH_NOTIFICATION_TEMPLATE_LEAVE_REQUEST_ACTION', 6);
define('PUSH_NOTIFICATION_TEMPLATE_RESCHEDULE_ACTION', 7);
define('PUSH_NOTIFICATION_TEMPLATE_CHANGE_SCHEDULE_ACTION', 8);

define('MAX_ROW_NOTIFICATION', 8);

// Arka - 240813 PAYMENT METHOD & GATEWAY
define('PAYMENT_METHOD_TYPE_MANUAL', 1);
define('PAYMENT_METHOD_TYPE_PAYMENT_GATEWAY', 2);
define('PAYMENT_METHOD_TYPE_IN_APPS', 3);

define('PAYMENT_GATEWAY_MIDTRANS', 1);
define('PAYMENT_GATEWAY_DANA', 2);
define('PAYMENT_GATEWAY_OVO', 3);

const MIDTRANS_PAYMENT_METHOD = [
    'gopay'=>11,
    'shopeepay'=>12,
    'bank_transfer_bca'=>15,
    'bank_transfer_bni'=>16,
    'bank_transfer_bri'=>18,
    'bank_transfer_permata'=>20,
    'echannel'=>21,
];

const JERSEY_COLOR = ['yellow', 'blue'];

const EMAIL_BRANCH_ADMIN = [
    2 => 'arkaarif69@gmail.com',
    3 => 'arkaarif69@gmail.com',
    4 => 'arkaarif69@gmail.com',
];

define('CLASSROOM_REPLACEMENT_REQUEST_TYPE_ID', 1); 
define('LEAVE_REQUEST_TYPE_ID', 2);
