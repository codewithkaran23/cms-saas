<?php
// core/Auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    public static function login($email, $password, $clinic_id = null) {
        $db = getDB();
        
        if ($clinic_id === null) {
            $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ? AND u.clinic_id IS NULL AND u.deleted_at IS NULL");
            $stmt->execute([$email]);
        } else {
            $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ? AND u.clinic_id = ? AND u.deleted_at IS NULL");
            $stmt->execute([$email, $clinic_id]);
        }
        
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['clinic_id'] = $user['clinic_id'];
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

    /**
     * Protect page and redirect to login
     */
    public static function protect($role = null) {
        if (!self::check()) {
            // Determine which login page to show
            if ($role === 'Super Admin') {
                redirect('admin/login.php');
            } else {
                redirect('login.php');
            }
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
