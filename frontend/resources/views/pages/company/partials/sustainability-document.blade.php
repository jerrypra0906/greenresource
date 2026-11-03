@php
    $docConfig = config('company.sustainability_document');
    $relativePath = $docConfig['path'] ?? 'assets/documents/sustainability-document.pdf';
    $absolutePath = public_path($relativePath);
    $pdfUrl = asset($relativePath);
    $pdfAvailable = is_file($absolutePath);
    $downloadName = $docConfig['download_filename'] ?? basename($relativePath);
@endphp

<div class="sustainability-document-content" aria-label="Sustainability policy document">
            @if($pdfAvailable)
                <div class="sustainability-document-panel">
                    <div class="sustainability-document-actions">
                        <a
                            href="{{ $pdfUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-primary sustainability-document-btn"
                        >
                            Preview full document
                        </a>
                        <a
                            href="{{ $pdfUrl }}"
                            download="{{ $downloadName }}"
                            class="btn-ghost sustainability-document-btn"
                        >
                            Download PDF
                        </a>
                    </div>
                </div>
            @else
                <div class="sustainability-document-panel sustainability-document-panel--unavailable" role="status">
                    <p class="about-paragraph sustainability-paragraph sustainability-paragraph-last">
                        Our sustainability document will be published here shortly. For immediate requests, please
                        <a href="{{ route('contact') }}">contact us</a>.
                    </p>
                </div>
            @endif
</div>
