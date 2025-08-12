<x-guest-layout>
    <div class="container mx-auto p-4">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6">
                <x-jet-authentication-card class="shadow-lg rounded-lg bg-white p-5 mt-4" style="margin: 0 15px;">
                    <x-slot name="logo">
                        <img src="{{ asset('img/logo.png') }}" alt="estasseguro" width="160">
                    </x-slot>

                    <x-jet-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <x-jet-label for="email" value="{{ __('Correo electrónico') }}" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus />
                        </div>

                        <div class="mt-4 relative">
                            <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                            <x-jet-input id="password" class="block mt-1 w-full pr-10" type="password" name="password"
                                required autocomplete="current-password" />
                            <br>
                            <span id="eye-icon"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1 cursor-pointer text-danger">
                                <i class="fas fa-eye text-red-600"></i>
                            </span>
                        </div>

                        {{-- ALERTA DE HORARIO --}}
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 text-sm mb-4">
                            <i class="fas fa-exclamation-triangle"></i>
                            El acceso al sistema está habilitado <strong>de lunes a viernes</strong>
                            de <strong>7:00 AM a 5:00 PM</strong>.
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
