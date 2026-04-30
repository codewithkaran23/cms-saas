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
            } elseif (isset($_SESSION['clinic_id'])) {
                // FALLBACK: If we are logged in as a Clinic Admin, use their session clinic
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
            $stmt = $db->prepare("SELECT * FROM clinics WHERE subdomain = ? AND status = 'active' AND deleted_at IS NULL");
            $stmt->execute([$subdomain]);
            $clinic = $stmt->fetch();

            if ($clinic) {
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
