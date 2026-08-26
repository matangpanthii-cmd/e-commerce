<?php

class Database {
    // -------------------------------------------------------
    // InfinityFree — แก้ค่าด้านล่างนี้ให้ตรงกับ Control Panel
    // -------------------------------------------------------

    // Host: ดูได้จาก InfinityFree cPanel → MySQL Databases
    // รูปแบบมักจะเป็น: sql***.infinityfree.net หรือ sqlXXX.epizy.com
    private $host = "sqlXXX.epizy.com";

    // ชื่อ Database: มักเริ่มต้นด้วย epiz_XXXXXXX_ชื่อ
    // ตัวอย่าง: epiz_12345678_lumina
    private $db_name = "epiz_XXXXXXX_lumina";

    // Username: เหมือนกับ Database username ใน cPanel
    // ตัวอย่าง: epiz_12345678
    private $username = "epiz_XXXXXXX";

    // Password: รหัสผ่านที่ตั้งไว้ตอนสร้าง Database User
    private $password = "YOUR_DB_PASSWORD";

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host
                 . ";dbname=" . $this->db_name
                 . ";charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

        } catch (PDOException $exception) {
            // ใน Production ไม่ควร echo error โดยตรง
            error_log("DB Connection error: " . $exception->getMessage());
            die("Database connection failed. Please try again later.");
        }

        return $this->conn;
    }
}
?>
