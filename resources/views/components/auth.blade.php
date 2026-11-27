<?php

/*
 * Nội dung các trang xác thực. Muốn đổi chữ, thêm/bớt ô nhập: sửa tại đây.
 * Sau khi sửa, nếu không thấy thay đổi: php artisan config:clear
 */
return [

    'login' => [
        'title'   => 'Đăng nhập',
        'action'  => '/login',
        'fields'  => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'autofocus' => true],
            ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'current-password',
             'aside' => ['label' => 'Quên mật khẩu?', 'href' => '/forgot-password']],
        ],
        'submit'  => 'Đăng nhập',
        'footer'  => ['text' => 'Chưa có tài khoản?', 'label' => 'Đăng ký', 'href' => '/register'],
    ],

    'register' => [
        'title'   => 'Tạo tài khoản',
        'action'  => '/register',
        'fields'  => [
            ['name' => 'name', 'label' => 'Họ và tên', 'autocomplete' => 'name', 'autofocus' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'],
            ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'],
            ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'],
        ],
        'submit'  => 'Đăng ký',
        'footer'  => ['text' => 'Đã có tài khoản?', 'label' => 'Đăng nhập', 'href' => '/login'],
    ],

    'forgot-password' => [
        'title'      => 'Quên mật khẩu',
        'subheading' => 'Nhập email của bạn, chúng tôi sẽ gửi liên kết để đặt lại mật khẩu.',
        'action'     => '/forgot-password',
        'fields'     => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'autofocus' => true],
        ],
        'submit'     => 'Gửi liên kết',
        'footer'     => ['label' => 'Quay lại đăng nhập', 'href' => '/login'],
    ],

    'reset-password' => [
        'title'   => 'Đặt lại mật khẩu',
        'action'  => '/reset-password',
        'token'   => true,
        'fields'  => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'],
            ['name' => 'password', 'label' => 'Mật khẩu mới', 'type' => 'password', 'autocomplete' => 'new-password', 'autofocus' => true],
            ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu mới', 'type' => 'password', 'autocomplete' => 'new-password'],
        ],
        'submit'  => 'Đặt lại mật khẩu',
    ],

    'logout' => [
        'title'      => 'Bạn đã đăng xuất',
        'subheading' => 'Hẹn gặp lại bạn!',
        'buttons'    => [
            ['label' => 'Đăng nhập lại', 'href' => '/login'],
            ['label' => 'Về trang chủ', 'href' => '/', 'outline' => true],
        ],
    ],

];