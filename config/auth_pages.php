<?php

return [
    'login' => [
        'title' => 'Chào mừng trở lại',
        'subheading' => 'Đăng nhập để tiếp tục hành trình học tiếng Trung.',
        'action' => '/login',
        'fields' => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'autofocus' => true],
            ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'current-password',
                'aside' => ['label' => 'Quên mật khẩu?', 'href' => '/forgot-password']],
        ],
        'submit' => 'Đăng nhập',
        'footer' => ['text' => 'Chưa có tài khoản?', 'label' => 'Đăng ký ngay', 'href' => '/register'],
    ],
    'register' => [
        'title' => 'Tạo tài khoản',
        'subheading' => 'Bắt đầu học tiếng Trung miễn phí.',
        'action' => '/register',
        'fields' => [
            ['name' => 'full_name', 'label' => 'Họ và tên', 'autocomplete' => 'name', 'autofocus' => true],
            ['name' => 'username', 'label' => 'Tên đăng nhập', 'autocomplete' => 'username'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'],
            ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'],
            ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'],
        ],
        'submit' => 'Tạo tài khoản',
        'footer' => ['text' => 'Đã có tài khoản?', 'label' => 'Đăng nhập', 'href' => '/login'],
    ],
    'forgot-password' => [
        'title' => 'Quên mật khẩu',
        'subheading' => 'Nhập email của bạn. Chúng tôi sẽ gửi liên kết đặt lại mật khẩu.',
        'action' => '/forgot-password',
        'fields' => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'autofocus' => true],
        ],
        'submit' => 'Gửi liên kết',
        'footer' => ['text' => '', 'label' => 'Quay lại đăng nhập', 'href' => '/login'],
    ],
    'reset-password' => [
        'title' => 'Tạo mật khẩu mới',
        'subheading' => 'Dùng mật khẩu mới và không chia sẻ với người khác.',
        'action' => '/reset-password',
        'token' => true,
        'fields' => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'],
            ['name' => 'password', 'label' => 'Mật khẩu mới', 'type' => 'password', 'autocomplete' => 'new-password', 'autofocus' => true],
            ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'],
        ],
        'submit' => 'Cập nhật mật khẩu',
    ],
];
