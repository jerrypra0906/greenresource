<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sustainability document (public PDF)
    |--------------------------------------------------------------------------
    |
    | Place the file under public/ using the path below, or set
    | SUSTAINABILITY_PDF_PATH in .env to another path relative to public/.
    |
    */
    'sustainability_document' => [
        'title' => env('SUSTAINABILITY_PDF_TITLE', 'Green Resources Sustainability Document'),
        'path' => env('SUSTAINABILITY_PDF_PATH', 'assets/documents/sustainability-document.pdf'),
        'download_filename' => env('SUSTAINABILITY_PDF_DOWNLOAD_NAME', 'Green-Resources-Sustainability-Document.pdf'),
    ],

];
