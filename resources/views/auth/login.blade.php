<x-guest-layout>
    <div class="auth-page">

        <style>
            /* Contenedor que pone el gradiente detrás sólo en la página de login */
            .auth-page {
                position: relative;
                min-height: 100vh;
            }

            /* Gradiente fijo detrás de todo (no se puede tapar) */
            .auth-page::before {
                content: "";
                position: fixed;
                inset: 0;
                z-index: -9999;
                background: linear-gradient(to right, #FF4B2B, #FF416C);
                background-attachment: fixed;
            }

            /* Forzamos que cualquier wrapper con bg-gray-100 quede transparente SOLO dentro de auth-page */
            .auth-page .bg-gray-100 {
                background: transparent !important;
            }

            /* Hacemos la tarjeta ligeramente translúcida para que se vea el degradado detrás */
            .auth-page .x-jet-authentication-card,
            .auth-page .jet-authentication-card,
            /* por si el componente tiene otro nombre en tu markup */
            .auth-page .bg-white {
                background: rgba(255, 255, 255, 0.95) !important;
            }
        </style>

        <!-- ------------------- tu markup original ------------------- -->
        <div class="min-h-screen flex items-center justify-center">
            <div class="w-full max-w-md px-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-6">
                        <x-jet-authentication-card class="shadow-lg rounded-lg bg-white p-5 mt-4" style="margin: 0 15px;">
                            <x-jet-validation-errors class="mb-4" />
                            <x-slot name="logo">
                                {{--  --}}
                            </x-slot>

                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="text-center mb-6">
                                    <img src="{{ asset('img/logo.png') }}" alt="estasseguro" width="160"
                                        class="mx-auto">
                                </div>
                                <div>
                                    <x-jet-label for="email" value="{{ __('Correo electrónico') }}" />
                                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autofocus />
                                </div>

                                <div class="mt-4 relative">
                                    <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                                    <x-jet-input id="password" class="block mt-1 w-full pr-10" type="password"
                                        name="password" required autocomplete="current-password" />
                                    <br>
                                    <span id="eye-icon"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1 cursor-pointer text-danger">
                                        <i class="fas fa-eye text-red-600"></i>
                                    </span>
                                </div>

                                <div
                                    class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 text-sm mb-4">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    El acceso al sistema está habilitado por los dias y horarios establecidos por el
                                    administrador.
                                </div>

                                <div class="block mt-4">
                                    <label for="remember_me" class="flex items-center">
                                        <x-jet-checkbox id="remember_me" name="remember" />
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Recuérdame') }}</span>
                                    </label>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-jet-button class="bg-red-600 hover:bg-red-700 text-white ml-4">
                                        {{ __('Ingresar') }}
                                    </x-jet-button>
                                </div>
                            </form>
                        </x-jet-authentication-card>
                    </div>
                </div>
            </div>
        </div>
        <!-- ----------------- fin markup original -------------------- -->

    </div> <!-- .auth-page -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#eye-icon');
            const passwordInput = document.querySelector('#password');
            const eyeIcon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash'); // Cambia el ícono a cerrado
                eyeIcon.classList.toggle('fa-eye'); // Cambia el ícono a abierto
            });
        });
    </script>
</x-guest-layout>
