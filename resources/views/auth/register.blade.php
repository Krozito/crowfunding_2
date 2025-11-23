<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="passwordChecker()">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                x-model="password"
                @input="evaluateStrength()"
            />

            <!-- Barra de progreso -->
            <div class="w-full h-2 mt-2 rounded bg-gray-300 relative">
                <div class="h-full rounded transition-all duration-300"
                    :style="`width: ${progress}%; background-color: ${color}`">
                </div>
            </div>

            <!-- Texto del nivel -->
            <p class="mt-1 text-sm font-semibold" :class="textColor" x-text="label"></p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- AlpineJS script -->
    <script>
        function passwordChecker() {
            return {
                password: '',
                progress: 0,
                label: '',
                color: 'red',
                textColor: 'text-red-600',

                evaluateStrength() {
                    let score = 0;

                    if (this.password.length >= 8) score++;
                    if (/[A-Z]/.test(this.password)) score++;
                    if (/[0-9]/.test(this.password)) score++;
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;

                    if (score <= 1) {
                        this.progress = 25;
                        this.label = 'Contraseña débil ❌';
                        this.color = '#dc2626'; 
                        this.textColor = 'text-red-600';
                    } else if (score === 2) {
                        this.progress = 50;
                        this.label = 'Contraseña moderada ⚠️';
                        this.color = '#d97706'; 
                        this.textColor = 'text-yellow-600';
                    } else if (score === 3) {
                        this.progress = 75;
                        this.label = 'Buena contraseña 👍';
                        this.color = '#16a34a';
                        this.textColor = 'text-green-600';
                    } else {
                        this.progress = 100;
                        this.label = 'Contraseña segura ✅';
                        this.color = '#059669';
                        this.textColor = 'text-green-600';
                    }
                }
            }
        }
    </script>
</x-guest-layout>
