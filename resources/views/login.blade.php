<x-guest-layout>
    <div class="login-page">
    <div class="login-brand">
        <div class="brand-content">
            <div class="brand-logo">
                DHD
            </div>
            <div class="brand-line"></div>
            <h1>DHD INTERIOR</h1>
            <p>Management System</p>
            <span>
                Interior • Architecture • Management
            </span>
        </div>
        <div class="brand-footer">
            © {{ date('Y') }} DHD Interior
        </div>
    </div>
    <div class="login-form-section">

        <div class="login-card">

            <div class="mobile-logo">
                DHD
            </div>

            <div class="login-heading">
                <h2>Welcome Back</h2>

                <p>
                    Sign in to access your management dashboard.
                </p>
            </div>


            <x-auth-session-status
                class="mb-4"
                :status="session('status')"
            />


            <form method="POST" action="{{ route('login') }}">
                @csrf


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email"
                    >

                    @if ($errors->get('email'))
                        <div class="error-message">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                </div>


                <div class="form-group">

                    <div class="password-label">

                        <label for="password">
                            Password
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif

                    </div>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >

                    @if ($errors->get('password'))
                        <div class="error-message">
                            {{ $errors->first('password') }}
                        </div>
                    @endif

                </div>

                <div class="remember-row">

                    <label>

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>
                            Remember me
                        </span>

                    </label>

                </div>

                <button type="submit" class="login-button">
                    Sign In
                </button>

            </form>


            <div class="login-bottom">
                Authorized access only
            </div>

        </div>

    </div>

</div>
```

</x-guest-layout>
