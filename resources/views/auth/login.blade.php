<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>{{ Request::is('admin/login') ? 'Login Admin' : 'Login Anggota' }} - Karang Taruna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('layouts.components.link')
</head>

@php
  $isAdmin = Request::is('admin/login');
  $loginAction = $isAdmin ? route('admin.login.post') : route('anggota.login.post');
  $inputLabel = $isAdmin ? 'Nama Pengguna atau Alamat Email' : 'Email';
  $inputType = $isAdmin ? 'text' : 'email';
  $inputName = $isAdmin ? 'login' : 'email';
@endphp

<body style="margin:0;font-family:'Google Sans',sans-serif;">

  <div class="container">

    <!-- LEFT -->
    <div class="left">
      <div class="overlay"></div>
      <div class="left-content">
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur, esse. Aspernatur doloribus distinctio
          quibusdam qui quod, repudiandae illum ad voluptate impedit temporibus officiis voluptatibus modi illo est
          adipisci animi asperiores?
        </p>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right">

      <a href="{{ route('home') }}">
        <div class="logo">
          <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" height="42">
        </div>
      </a>

      <div class="form-wrapper">
        <h1>{{ $isAdmin ? 'Login Admin' : 'Login Anggota' }}</h1>

        <form action="{{ $loginAction }}" method="POST">
          @csrf

          {{-- ALERT --}}
          @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
          @endif

          <div class="input-group">
            <label>{{ $inputLabel }}</label>
            <div class="input-field">
              <input type="{{ $inputType }}" name="{{ $inputName }}" value="{{ old($inputName) }}"
                placeholder="{{ $isAdmin ? 'Masukan Email atau Username' : 'Masukan Email' }}" required>
              <i class="fa-regular fa-user icon"></i>
            </div>
          </div>

          <div class="input-group">
            <label>Password</label>
            <div class="input-field">
              <input type="password" name="password" id="password" placeholder="Masukan Password" required>
              <i class="fa-regular fa-eye icon toggle"></i>
            </div>
          </div>

          <div class="action">
            <button type="submit">Masuk</button>
          </div>

          <p class="alt">
            @if($isAdmin)
              Bukan Admin?
              <a href="{{ route('anggota.login') }}">Login sebagai anggota</a>
            @else
              Anda Admin?
              <a href="{{ route('admin.login') }}">Login sebagai admin</a>
            @endif
          </p>

        </form>
      </div>
    </div>

  </div>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body,
    input,
    button,
    textarea,
    select,
    a {
      font-family: 'Google Sans', sans-serif !important;
    }

    body {
      height: 100vh;
    }

    .container {
      display: flex;
      height: 100vh;
    }

    /* LEFT */
    .left {
      width: 50%;
      background: url('{{ asset("assets-front/images/hero_bg.jpg") }}') center/cover no-repeat;
      position: relative;
      display: flex;
      align-items: flex-end;
      padding: 50px;
      color: white;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, .45);
    }

    .left-content {
      position: relative;
      max-width: 420px;
    }

    .left-content p {
      font-size: 14px;
      line-height: 1.7;
      margin-bottom: 25px;
    }

    .social i {
      margin-right: 15px;
      color: #ff6b00;
      font-size: 18px;
    }

    /* RIGHT */
    .right {
      width: 50%;
      background: #f6f7f8;
      padding: 70px 90px;
      position: relative;
    }

    .logo {
      position: absolute;
      top: 40px;
      right: 60px;
    }

    .form-wrapper {
      max-width: 460px;
      margin-top: 90px;
    }

    .form-wrapper h1 {
      font-size: 42px;
      font-weight: 700;
      color: #022648;
      margin-bottom: 40px;
    }

    /* INPUT */
    .input-group {
      margin-bottom: 25px;
    }

    .input-group label {
      font-size: 14px;
      margin-bottom: 8px;
      display: block;
    }

    .input-field {
      position: relative;
    }

    .input-field input {
      width: 100%;
      padding: 15px 45px 15px 15px;
      border-radius: 8px;
      border: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .04);
      font-size: 14px;
    }

    .input-field input:focus {
      outline: none;
      border-color: #022648;
      box-shadow: 0 0 0 3px rgba(9, 11, 98, .12);
    }

    .icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }

    /* BUTTON */
    .action {
      display: flex;
      align-items: center;
      gap: 25px;
      margin-top: 20px;
    }

    .action a {
      color: #022648;
      font-weight: 500;
      text-decoration: none;
    }

    .action button {
      background: #FFE701;
      color: #000;
      border: none;
      padding: 15px 50px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      font-size: 14px;
      transition: 0.3s;
    }

    .action button:hover {
      background: #FFE701;
    }

    .alt {
      margin-top: 25px;
      font-size: 14px;
    }

    .alt a {
      color: #022648;
      font-weight: 500;
      text-decoration: none;
    }

    .alert {
      background: #fee2e2;
      color: #991b1b;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {

      .container {
        flex-direction: column;
      }

      .left {
        display: none;
      }

      .right {
        width: 100%;
        padding: 40px 30px;
      }

      .form-wrapper {
        margin-top: 60px;
      }

    }
  </style>

  <script>
    const toggle = document.querySelector(".toggle");
    const password = document.getElementById("password");
    toggle.addEventListener("click", () => {
      if (password.type === "password") {
        password.type = "text";
        toggle.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        password.type = "password";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  </script>

</body>

</html>