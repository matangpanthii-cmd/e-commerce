<?php
require_once BASE_PATH . '/src/config/database.php';

class Setting {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Get all settings as a key => value associative array
     */
    public function getAllSettings(): array {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM site_settings");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Get all settings with full metadata (for admin panel)
     */
    public function getAllSettingsFull(): array {
        $stmt = $this->db->query("SELECT * FROM site_settings ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single setting value by key
     */
    public function get(string $key, string $default = ''): string {
        $stmt = $this->db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = :key LIMIT 1");
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? ($row['setting_value'] ?? $default) : $default;
    }

    /**
     * Update multiple settings at once. $data is an array of [key => value]
     */
    public function updateSettings(array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE site_settings SET setting_value = :value WHERE setting_key = :key"
        );
        foreach ($data as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
        return true;
    }
}
