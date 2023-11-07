<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Enigma admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Enigma Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="/images/hardware.png">
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="admin/css/app.css" />
    {{-- Bootstrap --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script> --}}
    {{-- FA Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <div class="my-auto">
                    <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="/images/gear2.svg">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        A few more clicks to
                        <br>
                        sign in to your account.
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Web-Based Hardware
                        Inventory
                        Management System</div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div
                    class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class=" font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your
                        account. <br> Web-Based Hardware
                        Inventory
                        Management System</div>

                    <div>
                        @if ($errors->has('error') && $errors->first('error') === 'Too many login attempts!')
                            <p id="timer" style="color: red"><i class="fa fa-exclamation-triangle pt-4"></i> Too
                                many
                                login attempts! Try
                                again after <span id="timeLeft"></span>
                                seconds
                            </p>
                        @else
                            <x-input-error :messages="$errors->get('error')" />
                        @endif
                    </div>
                    @if (session('error'))
                        <div class="text-danger mt-2">
                            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class=" mt-8">
                            <input autofocus type="text" name="email"
                                class=" login__input form-control py-3 px-4 block" placeholder="Email">
                            <x-input-error style="color: red" :messages="$errors->get('email')" class="mt-2" />
                            <input type="password" name="password"
                                class=" login__input form-control py-3 px-4 block mt-4" placeholder="Password">
                            <x-input-error style="color: red" :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class=" flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none"
                                    for="remember-me">{{ __('Remember me') }}</label>
                            </div>
                            {{-- @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                            @endif --}}
                        </div>
                        <div class=" mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit"
                                id="login-btn">Login</button>

                        </div>

                    </form>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>


    <!-- BEGIN: JS Assets-->
    <script src="admin/js/app.js"></script>
    <script src="./js/index.js"></script>
    <!-- END: JS Assets-->
</body>

</html>
