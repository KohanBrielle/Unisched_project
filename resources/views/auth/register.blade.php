<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register - My UniSched</title>
    <link rel="stylesheet" href="{{ asset('css/Group2_Style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@900&display=swap" rel="stylesheet">
</head>
<body>
    <video autoplay muted loop id="bg-video">
        <source src="{{ asset('videos/LSPU.mp4') }}" type="video/mp4">
    </video>

    <div class="box register-box"> 
        <div class="left">
            <div class="overlay"></div>
            <div class="content">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <h1>CREATE YOUR <br> UNISCHED <br> ACCOUNT</h1>
                <hr class="line">
            </div>
        </div>

        <div class="right register-right">
            <div class="header">
                <img src="{{ asset('images/logo2.png') }}" alt="LSPU Online">
                <p class="title">My <span class="logo-highlight">Uni</span>Sched</p>
                <p class="tagline">Student Registration</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf 

                <!-- Full Name -->
                <div class="input-field">
                    <i>👤</i>
                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                </div>

                <!-- Student ID -->
                <div class="input-field">
                    <i>🆔</i>
                    <input type="text" name="student_id" placeholder="Student ID (e.g. 2024-12345)" value="{{ old('student_id') }}" required>
                </div>

                <!-- Email Address -->
                <div class="input-field">
                    <i>✉️</i>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                </div>

                <!-- Password -->
                <div class="input-field">
                    <i>🔒</i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <!-- Confirm Password -->
                <div class="input-field">
                    <i>🔒</i>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                </div>

                @if ($errors->any())
                    <p style="color: #ff4d4d; font-size: 12px; margin-bottom: 10px;">{{ $errors->first() }}</p>
                @endif
                
                <button type="submit" class="btn">Register</button>

                <div style="margin-top: 15px; text-align: center;">
                    <p style="color: #cccccc; font-size: 13px; margin-bottom: 5px;">Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn-register" style="text-decoration: none;">Login Here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/Group2_js.js') }}"></script>
</body>
</html>