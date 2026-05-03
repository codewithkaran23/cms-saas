<?php
// core/Auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    public static function login($email, $password, $clinic_id = null) {
        $db = getDB();
        
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ? AND u.deleted_at IS NULL");
        $stmt->execute([$email]);
        
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['clinic_id'] = $user['clinic_id'];
            $_SESSION['require_reset'] = $user['require_reset']; // Store reset flag
            return true;
        }
        
        return false;
    }

    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    public static function shouldReset() {
        return isset($_SESSION['require_reset']) && $_SESSION['require_reset'] == 1;
    }


    public static function protect($role = null) {
        if (!self::check()) {
            redirect('login.php');
            exit;
        }

        // Force password reset if required
        if (self::shouldReset()) {
            redirect('change-password.php');
            exit;
        }
        
        if ($role && !self::hasRole($role)) {
            die("Unauthorized access.");
        }
    }

    public static function logout($target = '') {
        session_destroy();
        redirect($target);
        exit;
    }
}
