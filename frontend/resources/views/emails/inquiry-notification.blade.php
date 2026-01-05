<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Inquiry from Green Resources Website</title>
</head>
<body>
    <h2>New Inquiry Received</h2>
    
    <p><strong>Name:</strong> {{ $inquiry->name }}</p>
    @if($inquiry->company)
    <p><strong>Company:</strong> {{ $inquiry->company }}</p>
    @endif
    <p><strong>Email:</strong> {{ $inquiry->email }}</p>
    @if($inquiry->phone)
    <p><strong>Phone:</strong> {{ $inquiry->phone }}</p>
    @endif
    <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $inquiry->message }}</p>
    
    <hr>
    <p><small>Submitted on: {{ $inquiry->submitted_at->format('F j, Y \a\t g:i A') }}</small></p>
</body>
</html>

