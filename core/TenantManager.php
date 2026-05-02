<?php
// core/TenantManager.php

class TenantManager {
    private static $currentClinic = null;

    public static function identify() {
        // For individual user system, we always return the primary practice
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM clinics WHERE deleted_at IS NULL LIMIT 1");
        $stmt->execute();
        $clinic = $stmt->fetch();

        if ($clinic) {
            self::$currentClinic = $clinic;
            return ['type' => 'doctor', 'data' => $clinic];
        }

        return ['type' => 'platform'];
    }

    public static function getClinic() {
        return self::$currentClinic;
    }
}
