<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="form-control">
            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-medium">Password</label>
            <input type="password" name="password" id="password" required class="form-control">
            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
            <label class="form-check-label small" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
</x-guest-layout>
