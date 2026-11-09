<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/error.css')}}">
    </head>
    <body class="antialiased font-sans">
        <div class="md:flex min-h-screen">
            <div class="w-full bg-white flex items-center justify-center text-center">
                <div class="max-w-sm m-8">
                    <div class="text-black text-5xl md:text-15xl font-black">
                        @yield('code', __('Oh no'))
                    </div>

                    <div class="w-full h-1 bg-purple-light my-3 md:my-6"></div>

                    <p class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal text-center">
                        @yield('message')
                    </p>

                    @php
                        $user = null;
                        try {
                            $user = \Illuminate\Support\Facades\Auth::guard('admin')->user() ?: \Illuminate\Support\Facades\Auth::user();
                        } catch (\Throwable $e) {}

                        $currentPath = request()->path();
                        $targetLoginUrl = route('login');
                        $buttonLabel = __('Return to Client Login');

                        if ($user) {
                            if ($user->hasRole('admin') || $user->hasRole('attorney') || $user->id === 1) {
                                $targetLoginUrl = route('admin.dashboard');
                                $buttonLabel = __('Return to Admin Command');
                            } elseif ($user->hasRole('staff') && !$user->hasRole('client')) {
                                $targetLoginUrl = route('staff.dashboard');
                                $buttonLabel = __('Return to Staff Workspace');
                            } else {
                                $targetLoginUrl = route('client.dashboard');
                                $buttonLabel = __('Return to Client Portal');
                            }
                        } else {
                            if (str_starts_with($currentPath, 'admin')) {
                                $targetLoginUrl = route('admin.login');
                                $buttonLabel = __('Return to Admin Login');
                            } elseif (str_starts_with($currentPath, 'staff')) {
                                $targetLoginUrl = route('staff.login');
                                $buttonLabel = __('Return to Staff Login');
                            } else {
                                $targetLoginUrl = route('login');
                                $buttonLabel = __('Return to Client Login');
                            }
                        }
                    @endphp

                    <a href="{{ $targetLoginUrl }}">
                        <button class="bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-grey-light hover:border-grey rounded-lg">
                            {{ $buttonLabel }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
