<?php
// core/TenantManager.php

class TenantManager {
    private static $currentClinic = null;

    public static function identify() {
        $host = $_SERVER['HTTP_HOST'];
        $parts = explode('.', $host);
        
        $subdomain = null;

        // 1. Check if we are on localhost
        if ($host === 'localhost' || $host === '127.0.0.1') {
            if (isset($_GET['clinic'])) {
                $subdomain = $_GET['clinic'];
            } elseif (isset($_GET['admin'])) {
                return ['type' => 'super_admin'];
            } elseif (isset($_SESSION['clinic_id']) && $_SERVER['SCRIPT_NAME'] !== '/cms-saas/index.php') {
                // FALLBACK: Use session only if we are NOT on the main platform landing page
                $db = getDB();
                $stmt = $db->prepare("SELECT * FROM clinics WHERE id = ? AND deleted_at IS NULL");
                $stmt->execute([$_SESSION['clinic_id']]);
                $clinic = $stmt->fetch();
                if ($clinic) {
                    self::$currentClinic = $clinic;
                    return ['type' => 'clinic', 'data' => $clinic];
                }
            }
        } else {
            // 2. Subdomain Logic
            if (count($parts) >= 2) {
                $subdomain = $parts[0];
                if ($subdomain === 'admin') {
                    return ['type' => 'super_admin'];
                }
            }
        }

        if ($subdomain) {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM clinics WHERE subdomain = ? AND deleted_at IS NULL");
            $stmt->execute([$subdomain]);
            $clinic = $stmt->fetch();

            if ($clinic) {
                // If clinic is pending, only allow access if logged in as the owner
                if ($clinic['status'] === 'pending') {
                    if (!isset($_SESSION['clinic_id']) || $_SESSION['clinic_id'] != $clinic['id']) {
                        return ['type' => 'platform'];
                    }
                }

                self::$currentClinic = $clinic;
                return ['type' => 'clinic', 'data' => $clinic];
            }
        }

        return ['type' => 'platform'];
    }

    public static function getClinic() {
        return self::$currentClinic;
    }
}
