<?php
// config/mail.php

return [
    'smtp_host' => 'smtp.gmail.com',         // e.g. smtp.gmail.com
    'smtp_port' => 587,                      // 587 for TLS, 465 for SSL
    'smtp_user' => 'gautamkholi410@gmail.com',   // Your full SMTP email
    'smtp_pass' => 'nzse gavt ogzn vkie',      // Your SMTP password (or App Password for Gmail)
    'from_email' => 'noreply@medos.com',
    'from_name' => 'MedOS Clinical System',
    'use_smtp' => true                     // Set to false to use default PHP mail()
];
