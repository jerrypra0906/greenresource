<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Basic HTML sanitization similar in spirit to HTMLPurifier.
     *
     * - Allows a safe subset of tags for rich content
     * - Strips dangerous attributes like on* and javascript: URLs
     */
    public static function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        // Prefer HTMLPurifier if available
        if (class_exists(\HTMLPurifier::class)) {
            static $purifier = null;

            if ($purifier === null) {
                $config = \HTMLPurifier_Config::createDefault();

                // Allowed elements and attributes for rich content
                $config->set('HTML.Allowed', 'p,br,strong,b,em,i,u,ol,ul,li,a[href|title],h1,h2,h3,h4,h5,h6,span[class|style],div[class|style],img[src|alt|title|width|height]');

                // Forbid dangerous attributes and protocols
                // HTMLPurifier automatically blocks javascript: and other dangerous URI schemes
                $config->set('CSS.AllowedProperties', ['text-align', 'margin', 'padding', 'color', 'background-color', 'font-weight', 'font-style', 'text-decoration']);
                $config->set('AutoFormat.RemoveEmpty', true);

                // Use Laravel's storage directory for HTMLPurifier cache (better permissions)
                $cacheDir = storage_path('app/htmlpurifier');
                if (!is_dir($cacheDir)) {
                    @mkdir($cacheDir, 0755, true);
                }
                $config->set('Cache.SerializerPath', $cacheDir);

                $purifier = new \HTMLPurifier($config);
            }

            return $purifier->purify($html);
        }

        // Fallback basic sanitization if HTMLPurifier is not available
        $allowedTags = '<p><br><br/><strong><b><em><i><u><ol><ul><li><a><h1><h2><h3><h4><h5><h6><span><div><img>';
        $clean = strip_tags($html, $allowedTags);
        $clean = preg_replace('/\s*on\w+="[^"]*"/i', '', $clean);
        $clean = preg_replace("/\s*on\w+='[^']*'/i", '', $clean);
        $clean = preg_replace('/(href|src)\s*=\s*"(javascript:[^"]*)"/i', '$1="#"', $clean);
        $clean = preg_replace("/(href|src)\s*=\s*'(javascript:[^']*)'/i", '$1=\"#\"', $clean);

        return $clean;
    }
}


