<?php

return [
    'nav' => [
        'login' => 'Log in',
        'register' => 'Sign up',
        'account' => 'My account',
        'orders' => 'My orders',
        'profile' => 'Profile',
        'logout' => 'Log out',
        'admin' => 'Admin panel',
        'menu' => 'Account menu',
    ],

    'login' => [
        'title' => 'Log in to your account',
        'lead' => 'Track your orders and place new ones in a few clicks.',
        'remember' => 'Remember me',
        'forgot' => 'Forgot your password?',
        'submit' => 'Log in',
        'no_account' => 'Don’t have an account?',
        'create_account' => 'Sign up',
    ],

    'register' => [
        'title' => 'Create an account',
        'lead' => 'Sign up and track the status of all your orders in one place.',
        'terms' => 'I agree to the terms of use and privacy policy',
        'submit' => 'Create account',
        'has_account' => 'Already have an account?',
        'login' => 'Log in',
        'password_hint' => 'At least 8 characters, with letters and numbers.',
        'benefits' => [
            'All your orders in one place',
            'Email updates when the status changes',
            'No need to fill in your details again next time',
        ],
    ],

    'forgot' => [
        'title' => 'Reset your password',
        'lead' => 'Enter your email and we will send you a link to reset your password.',
        'submit' => 'Send reset link',
        'sent' => 'If an account exists for this email, a reset link has been sent. Check your inbox (and spam folder).',
        'back' => 'Back to login',
    ],

    'reset' => [
        'title' => 'Set a new password',
        'submit' => 'Reset password',
    ],

    'reset_mail' => [
        'subject' => 'Password reset — Hamid Agency',
        'greeting' => 'Hello, :name!',
        'intro' => 'We received a password reset request for your account.',
        'action' => 'Reset password',
        'expire' => 'This link is valid for :count minutes.',
        'outro' => 'If you did not request a password reset, no further action is required.',
    ],

    'dashboard' => [
        'title' => 'My account',
        'welcome' => 'Welcome, :name',
        'lead' => 'Here you can follow the progress of your orders and place new ones.',
        'new_order' => 'New order',
        'orders' => 'My orders',
        'empty_title' => 'You have no orders yet',
        'empty_text' => 'Website, mobile app, hosting or domain — place your first order.',
        'total' => 'Total',
    ],

    'orders' => [
        'number' => 'Order #:number',
        'service' => 'Service',
        'date' => 'Date',
        'status' => 'Status',
        'details' => 'Order details',
        'message' => 'Your notes',
        'contact' => 'Contact details',
        'back' => 'All orders',
        'view' => 'View',
        'progress' => 'Order progress',
        'help' => 'Have a question? Write to us and mention your order number.',
    ],

    'statuses' => [
        'new' => 'Received',
        'contacted' => 'In progress',
        'completed' => 'Completed',
    ],

    'status_hints' => [
        'new' => 'Your request has reached us. We will review it and contact you shortly.',
        'contacted' => 'We have been in touch and are working on your order.',
        'completed' => 'Your order is complete. Thank you for choosing us!',
    ],

    'profile' => [
        'title' => 'Profile',
        'details' => 'Personal details',
        'password' => 'Change password',
        'save' => 'Save',
        'update_password' => 'Update password',
        'language_hint' => 'Emails will be sent in this language.',
    ],

    'quote' => [
        'guest_title' => 'Want to track your orders?',
        'guest_text' => 'Create an account or log in to see your order status at any time.',
        'logged_in' => 'This order will be added to your account (:email).',
    ],

    'flash' => [
        'registered' => 'Welcome! Your account has been created.',
        'profile_updated' => 'Your profile has been updated.',
        'password_updated' => 'Your password has been changed.',
        'password_reset' => 'Your password has been reset and you are now logged in.',
    ],

    'mail' => [
        'received' => [
            'subject' => 'We received your order — #:number',
            'heading' => 'Thank you, :name!',
            'text' => 'Your ":service" request (#:number) has been received. We will contact you shortly.',
        ],
        'status' => [
            'subject' => 'Your order status was updated — #:number',
            'heading' => 'Order #:number',
            'text' => 'New status: **:status**',
        ],
        'view_order' => 'View order',
        'signature' => 'Best regards, the Hamid Agency team',
    ],

    'attributes' => [
        'name' => 'full name',
        'email' => 'email',
        'phone' => 'phone',
        'company' => 'company',
        'password' => 'password',
        'current_password' => 'current password',
        'terms' => 'terms',
        'locale' => 'language',
    ],

    'fields' => [
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'current_password' => 'Current password',
        'new_password' => 'New password',
        'language' => 'Language',
    ],
];
