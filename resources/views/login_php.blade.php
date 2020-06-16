<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>

<body>
    <hr>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            {{-- <a href="{{ $url }}" class="btn btn-primary"><i class="fa fa-facebook"></i> Facebook</a> --}}
            <a href="/auth/redirect/facebook" class="btn btn-primary"><i class="fa fa-facebook"></i> Facebook</a>
        </div>
    </div>
</body>

</html>