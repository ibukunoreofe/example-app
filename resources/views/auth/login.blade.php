{{--<div id="fb-root"></div>--}}
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce="Bx8LWIyu"></script>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <a href="{{ url('auth/google')  }}" type="button" class="login-with-google-btn" >
            Sign in with Google
        </a>

        <br/>
        <br/>
        <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
        </fb:login-button>

{{--        <div id="status">--}}
{{--        </div>--}}

        <div id="apple-sign-in-button" class="apple-sign-in" data-color="black" data-border="false" data-type="sign in"></div>


    </form>
</x-guest-layout>

<script>

    function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
        console.log('statusChangeCallback');
        console.log(response);                   // The current login status of the person.
        if (response.status === 'connected') {   // Logged into your webpage and Facebook.
            /// testAPI();
        } else {                                 // Not logged into your webpage or we are unable to tell.
           // document.getElementById('status').innerHTML = 'Please log ' +
           //     'into this webpage.';
        }
    }


    function checkLoginState() {               // Called when a person is finished with the Login Button.
        FB.getLoginStatus(function(response) {   // See the onlogin handler
            statusChangeCallback(response);
        });
    }


    window.fbAsyncInit = function() {
        FB.init({
            appId      : '{{ env('FACEBOOK_CLIENT_ID')  }}',
            cookie     : true,                     // Enable cookies to allow the server to access the session.
            xfbml      : true,                     // Parse social plugins on this webpage.
            version    : 'v18.0'           // Use this Graph API version for this call.
        });


        FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
            statusChangeCallback(response);        // Returns the login status.
        });
    };

    // function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    //     console.log('Welcome!  Fetching your information.... ');
    //     FB.api('/me', function(response) {
    //         console.log(response);
    //         console.log('Successful login for: ' + response.name);
    //         document.getElementById('status').innerHTML =
    //             'Thanks for logging in, ' + response.name + '!';
    //     });
    // }

</script>

<script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
<script type="text/javascript">
    // https://developer.apple.com/documentation/sign_in_with_apple/sign_in_with_apple_js/configuring_your_webpage_for_sign_in_with_apple
    AppleID.auth.init({
        clientId: 'YOUR_CLIENT_ID',
        scope: 'name email', // Specify the scope of information you want to retrieve
        redirectURI: 'https://your-redirect-uri.com', // Specify your redirect URI
        state: 'your-state', // Optionally, include a state to maintain the user's state between the request and callback
    });

    document.getElementById('apple-sign-in-button').addEventListener('click', function () {
        AppleID.auth.signIn();
    });
</script>


