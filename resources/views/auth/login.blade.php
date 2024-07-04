<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tp Salaire</title>
</head>
<body>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700,600" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{asset('css/auth.css')}}">

<form method="post" action="{{route('handleLogin')}}">

    @csrf
    @method('POST')

    <div class="box">
        <h1>Espace de connection</h1>
        

        @if (Session::get('success_message'))
            <b style="font-size: 13px; color: rgb(81, 185, 162)" > {{ Session::get('error_msg') }} </b>

        @endif

        @if (Session::get('error_msg'))
            <b style="font-size: 13px; color: rgb(185, 81, 81)" > {{ Session::get('error_msg') }} </b>

        @endif

        <input type="email" name="email" class="email" />

        <input type="password" name="password" class="email" />

        <div class="btn-container">
            <button type="submit"> Connexion</button>
        </div>

        <!-- End Btn -->
        <!-- End Btn2 -->
    </div>
    <!-- End Box -->
</form>
</body>
</html>
