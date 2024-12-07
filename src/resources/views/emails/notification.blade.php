<!DOCTYPE html>
<html>
<head>
    <title>{{ $subjectContent }}</title>
</head>
<body>
    <h1>{{ $subjectContent }}</h1>
    <p>{!! nl2br(e($messageContent)) !!}</p>
</body>
</html>
