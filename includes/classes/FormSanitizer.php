<?php

class FormSanitizer
{
    public static function sanitizeFormString(string $inputText): string
    {
        $inputText = strip_tags($inputText);
        $inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);

        return $inputText;
    }

    public static function sanitizeFormUsername(string $inputText): string
    {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(' ', '', $inputText);

        return $inputText;
    }

    public static function sanitizeFormPassword(string $inputText): string
    {
        $inputText = strip_tags($inputText);

        return $inputText;
    }

    public static function sanitizeFormEmail(string $inputText): string
    {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(' ', '', $inputText);

        return $inputText;
    }
}