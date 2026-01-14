<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'type',
        'title',
        'body',
        'media_id',
        'order',
    ];

    /**
     * Render-ready HTML for body content.
     *
     * Fixes common broken image paths inserted into the rich-text body:
     * - "storage/..."      -> "/storage/..."
     * - "media/..."        -> "/storage/media/..."
     * - "storage/media/..."-> "/storage/media/..."
     *
     * Leaves absolute URLs (http/https), protocol-relative (//), and data: URIs untouched.
     */
    public function getBodyHtmlAttribute(): ?string
    {
        if ($this->body === null || $this->body === '') {
            return $this->body;
        }

        $html = $this->body;

        $html = preg_replace_callback(
            '/\bsrc=(["\'])([^"\']+)\1/i',
            function (array $m) {
                $quote = $m[1];
                $src = $m[2];

                $trimmed = trim($src);
                if ($trimmed === '') {
                    return 'src=' . $quote . $src . $quote;
                }

                // Leave absolute/protocol-relative/data URIs untouched
                if (preg_match('/^(https?:)?\\/\\//i', $trimmed) || str_starts_with($trimmed, 'data:')) {
                    return 'src=' . $quote . $src . $quote;
                }

                // Normalize common relative paths
                if (str_starts_with($trimmed, 'storage/')) {
                    $trimmed = '/' . $trimmed; // -> /storage/...
                } elseif (str_starts_with($trimmed, 'media/')) {
                    $trimmed = '/storage/' . $trimmed; // -> /storage/media/...
                } elseif (str_starts_with($trimmed, 'storage/media/')) {
                    $trimmed = '/storage/' . substr($trimmed, strlen('storage/')); // -> /storage/media/...
                }

                return 'src=' . $quote . $trimmed . $quote;
            },
            $html
        );

        return $html;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}

