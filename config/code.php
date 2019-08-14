<?php

return [
    'login_validation' => [
        'email_required' => [
            "code" => "LOGIN_ROUTE-EMAIL-REQUIRED",
            "message" => "The email field is required."
        ],
        'password_required' => [
            "code" => "LOGIN_ROUTE-PASSWORD-REQUIRED",
            "message" => "The password field is required."
        ],
        'password_min' => [
            "code" => "LOGIN_ROUTE-PASSWORD-MIN",
            "message" => "The password must be at least 8 characters."
        ],
        'email_email' => [
            "code" => "LOGIN_ROUTE-EMAIL-EMAIL",
            "message" => "The email must be a valid email address."
        ],
    ],
    'create_post_validation' => [
        'title_required' => [
            "code" => "CREATE_POST-TITLE-REQUIRED",
            "message" => "The title field is required."
        ],
        'description_required' => [
            "code" => "CREATE_POST-DESCRIPTION-REQUIRED",
            "message" => "The description field is required."
        ],
        'title_min' => [
            "code" => "CREATE_POST-TITLE-MIN",
            "message" => "The title must be at least 5 characters."
        ],
        'title_max' => [
            "code" => "CREATE_POST-TITLE-MAX",
            "message" => "The title may not be greater than 10 characters."
        ],
        'description_min' => [
            "code" => "CREATE_POST-DESCRIPTION-MIN",
            "message" => "The description must be at least 15 characters."
        ],
        'image_mimes' => [
            "code" => "CREATE_POST-IMAGE_NAME.0-MIMES",
            "message" => "The image_name.0 must be a file of type: jpeg, jpg, png."
        ],
        'tagInput_min' => [
            "code" => "CREATE_POST-TAGINPUT-MIN",
            "message" => "The tag input must be at least 2 characters."
        ],
    ],

    'update_post_validation' => [
        'title_required' => [
            "code" => "UPDATE_POST-TITLE-REQUIRED",
            "message" => "The title field is required."
        ],
        'description_required' => [
            "code" => "UPDATE_POST-DESCRIPTION-REQUIRED",
            "message" => "The description field is required."
        ],
        'title_min' => [
            "code" => "UPDATE_POST-TITLE-MIN",
            "message" => "The title must be at least 5 characters."
        ],
        'title_max' => [
            "code" => "UPDATE_POST-TITLE-MAX",
            "message" => "The title may not be greater than 10 characters."
        ],
        'description_min' => [
            "code" => "UPDATE_POST-DESCRIPTION-MIN",
            "message" => "The description must be at least 15 characters."
        ],
        'image_mimes' => [
            "code" => "UPDATE_POST-IMAGE_NAME.0-MIMES",
            "message" => "The image_name.0 must be a file of type: jpeg, jpg, png."
        ],
        'tagInput_min' => [
            "code" => "UPDATE_POST-TAGINPUT-MIN",
            "message" => "The tag input must be at least 2 characters."
        ],
    ]
];
