<?php

/*
 * Cấu hình chuẩn cho Cloudinary Laravel
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình này ánh xạ các biến môi trường từ file .env vào thư viện.
    |
    */

    // URL tổng hợp (Thường dùng cái này là đủ)
    'cloud_url' => env('CLOUDINARY_URL'),

    // Preset cho upload (nếu dùng upload từ frontend, backend ít dùng)
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    // URL để nhận thông báo (Webhook) - để null nếu không dùng
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    // [QUAN TRỌNG] Cấu hình chi tiết để sửa lỗi "Undefined array key 'cloud'"
    // Thư viện đôi khi sẽ tìm mảng 'cloud' này thay vì dùng 'cloud_url'
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'url'        => [
            'secure' => true // Luôn dùng https
        ]
    ],
    
    'home' => env('CLOUDINARY_HOME'),
];