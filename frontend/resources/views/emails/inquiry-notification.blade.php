<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Inquiry from {{ config('app.name', 'Green Resources') }}</title>
</head>
<body>
    <p>Dear Admin Team,</p>

    <p>
        You have received a new inquiry from a website visitor.
        Please find the details below:
    </p>

    <h3>Inquiry Details</h3>

    <p><strong>Name:</strong> {{ $inquiry->name }}</p>
    <p><strong>Email:</strong> {{ $inquiry->email }}</p>
    <p><strong>Phone Number:</strong> {{ $inquiry->phone ?: '—' }}</p>
    <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>

    <p><strong>Message:</strong></p>
    <p style="white-space: pre-wrap;">{{ $inquiry->message }}</p>

    <p>
        <strong>Submitted On (UTC+8):</strong>
        {{ $inquiry->submitted_at->timezone('Asia/Singapore')->format('F j, Y \a\t g:i A') }}
        <br>
        <strong>Source:</strong>
        <a href="{{ route('admin.inquiries.show', $inquiry) }}">Open in CMS</a>
    </p>

    <p>Please review and follow up as needed.</p>

    <p>Thank you,<br>
    <strong>System Notification</strong><br>
    {{ config('app.name', 'Green Resources') }}</p>
</body>
</html>

