<?php
namespace Conex\MiniFramework\utils;

class Flash {
    
    public static function set($type, $message) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['flash'][$type] = $message;
        error_log("Flash set: $type - $message"); 
    }
    
    public static function get($type = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if ($type) {
            $message = $_SESSION['flash'][$type] ?? null;
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        error_log("Flash get all: " . print_r($messages, true)); 
        return $messages;
    }
    
    public static function has($type = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if ($type) {
            return isset($_SESSION['flash'][$type]);
        }
        
        return !empty($_SESSION['flash']);
    }
    
    public static function success($message) {
        self::set('success', $message);
    }
    
    public static function error($message) {
        self::set('error', $message);
    }
    
    public static function warning($message) {
        self::set('warning', $message);
    }
    
    public static function info($message) {
        self::set('info', $message);
    }
}