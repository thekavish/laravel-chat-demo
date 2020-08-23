@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script defer>
        // Retrieve Firebase Messaging object.
        const messaging = window.firebase.messaging();
        // Add the public key generated from the console here.
        messaging.usePublicVapidKey("{{ env('FIREBASE_WEB_VAPID_KEY') }}");

        sendTokenToServer = (token) => {
            console.log(token);
        };

        retrieveToken = () => {
            // Get Instance ID token. Initially this makes a network call, once retrieved
            // subsequent calls to getToken will return from cache.
            messaging.getToken().then((currentToken) => {
                if (currentToken) {
                    sendTokenToServer(currentToken);
                    // updateUIForPushEnabled(currentToken);
                } else {
                    // Show permission request.
                    console.log('No Instance ID token available. Request permission to generate one.');
                    // Show permission UI.
                    // updateUIForPushPermissionRequired();
                    // setTokenSentToServer(false);
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // showToken('Error retrieving Instance ID token. ', err);
                // setTokenSentToServer(false);
            });
        };

        // Callback fired if Instance ID token is updated.
        messaging.onTokenRefresh(() => {
            retrieveToken();
        });

        retrieveToken();

        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);
        });

        Echo.channel('open-channel').listen('SendMessage', (data) => {
            console.log(data);
        });
    </script>
@endpush
