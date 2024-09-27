<?php

namespace common;

class FeatureUtil {
    public static function displayMessage($type, $message) {
        if (isset($message)) {
            $class = $type === 'error' ? 'error-message' : 'handle-message';
            echo "<div class=\"$class\">" . htmlspecialchars($message) . "</div>";
        }
    }

}