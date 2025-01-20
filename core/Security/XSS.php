<?php
// /core/Security/XSS.php
class XSS {
    public static function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}