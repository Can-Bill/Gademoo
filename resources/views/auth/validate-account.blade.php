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

<form method="post" action="{{route('submitDefineAccess', $email)}}">

    @csrf
    @method('POST')

    <div class="box">
        <h1>Définissez vos acces</h1>
        

        @if (Session::get('error_msg'))
            <b style="font-size: 13px; color: rgb(185, 81, 81)" > {{ Session::get('error_msg') }} </b>

        @endif


        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="email" value="{{ $email }}" readonly/>

        </div>
        
        <div class="form-group">
            <label for="">Code</label>
            <input type="text" name="code" class="email" value=" {{ old('code') }}" />
            @error('code')
                <span class="text text-danger"> {{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" name="confirm_password" class="email" />
            @error('password')
                <span class="text text-danger"> {{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Mot de passe de confirmation</label>
            <input type="password" name="confirm_password" class="email" />
            @error('confirm_password')
                <span class="text text-danger"> {{$message}}</span>
            @enderror
        </div>

        

        <div class="btn-container">
            <button type="submit"> Valider</button>
        </div>

        <!-- End Btn -->
        <!-- End Btn2 -->
    </div>
    <!-- End Box -->
</form>
</body>
</html>
<style>
    .form-group{
        display: flex;
        flex-direction: colum;
        justify-content: center;
        align-items: center;
        margin-bottom: 1rem;
    }
    .text-danger{
        color: rgb(185, 81, 81)|important;
    } 
</style>