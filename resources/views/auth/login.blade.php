<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My UniSched</title>
    <link rel="stylesheet" href="{{ asset('css/Group2_Style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@900&display=swap" rel="stylesheet">
</head>
<body>
    <video autoplay muted loop id="bg-video">
        <source src="{{ asset('videos/LSPU.mp4') }}" type="video/mp4">
    </video>

    <div class="box"> 
        <div class="left">
            <div class="overlay"></div>
            <div class="content">
                <img src="images/logo.png" alt="Logo" class="logo">
                <h1>SIGN-IN TO YOUR <br> UNISCHED <br> ACCOUNT</h1>
                <hr class="line">
            </div>
        </div>

        <div class="right">
            <div class="header">
                <img src="images/logo2.png" alt="LSPU Online">
                <p class="title">My <span class="logo-highlight">Uni</span>Sched</p>
                <p class="tagline">Your Scheduling Web Application</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf 
    
                <div class="input-field">
                    <i>👤</i>
                    <!-- email -->
                    <input type="email" name="email" placeholder="Email Address" required autofocus>
                </div>

                <div class="input-field">
                    <i>🔒</i>
                    <!-- password -->
                    <input type="password" name="password" placeholder="........" required>
                </div>

                @if ($errors->any())
                    <p style="color: red; font-size: 12px;">{{ $errors->first() }}</p>
                @endif

                <a href="{{ route('password.request') }}" class="forgot">Forgot Password?</a>
                
                <button type="submit" class="btn">Login</button>

                <div style="margin-top: 15px; text-align: center;">
                    <p style="color: #cccccc; font-size: 13px; margin-bottom: 5px;">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="btn-register">Register Now</a>
                </div>
            </form>

            <p class="credits">A Project in fulfillment of the requirements for CpE 8 by BS CpE 2A Group 2 A.Y. 2025-2026</p>
        </div>
    </div>
    <script src="{{ asset('js/Group2_js.js') }}"></script>
</body>