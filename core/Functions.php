<?php
// core/Functions.php

/**
 * Get the project root URL (e.g. http://localhost/cms-saas/)
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // We want the URL to the project root, not the current folder
    // This looks for 'cms-saas' in the path
    $script_name = $_SERVER['SCRIPT_NAME'];
    $pos = strpos($script_name, '/cms-saas');
    $project_root = ($pos !== false) ? '/cms-saas' : '';
    
    return $protocol . "://" . $host . $project_root . "/" . ltrim($path, '/');
}

/**
 * Safe redirect to a project path
 */
function redirect($path) {
    header("Location: " . base_url($path));
    exit;
}

/**
 * Sanitize output
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a random secure password
 */
function generate_random_password($length = 10) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'), 0, $length);
}

/**
 * Send credentials to user via email
 */
function send_credentials_email($email, $name, $password, $role) {
    global $mail_config;
    
    $login_url = base_url('login.php');
    $subject = "Welcome to MedOS - Your Clinical Credentials";
    
    $message = "
    <html>
    <head>
        <title>Welcome to MedOS</title>
    </head>
    <body style='font-family: sans-serif; line-height: 1.6; color: #334155;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded: 12px;'>
            <h2 style='color: #0d9488;'>Welcome, $name!</h2>
            <p>Your account as a <strong>$role</strong> has been created successfully at our clinic.</p>
            <div style='background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <p style='margin: 0;'><strong>Login Email:</strong> $email</p>
                <p style='margin: 10px 0 0 0;'><strong>Temporary Password:</strong> <span style='color: #0d9488; font-family: monospace;'>$password</span></p>
            </div>
            <p>You can sign in to your dashboard here:</p>
            <a href='$login_url' style='display: inline-block; background: #0d9488; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Sign In to MedOS</a>
            <p style='margin-top: 30px; font-size: 12px; color: #94a3b8;'>Please change your password after your first login for security reasons.</p>
        </div>
    </body>
    </html>
    ";

    // If SMTP is enabled, use the Mailer class. Otherwise fallback to mail()
    if (isset($mail_config['use_smtp']) && $mail_config['use_smtp']) {
        return Mailer::send($email, $subject, $message);
    }

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $mail_config['from_name'] . " <" . $mail_config['from_email'] . ">" . "\r\n";

    return mail($email, $subject, $message, $headers);
}
