<?php

namespace App;

// Possibility to add flash notification messages into $_SESSION for user
class Flash {

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';

    public static function addMessage($message, $type = 'success') {
        // Check if the message array exists in the $_session
        if (!isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = array();
        }
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $type
        ];
    }

    // Return the messages set into the $_SESSION
    public static function getMessages() {
        if (isset($_SESSION['flash_notifications'])) {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);
            return $messages;
        }
    }
}