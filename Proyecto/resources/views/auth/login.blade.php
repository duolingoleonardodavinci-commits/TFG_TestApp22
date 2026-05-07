@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <h1>Iniciar sesión</h1>

    <x-errores />

    <form method="POST" action="{{ route('auth.login') }}" class="form-card">
        @csrf

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email"
                    name="email"
                    placeholder="ejemplo@email.com"
                    value="{{ old('email') }}"
                    class="form-input @error('email') incorrect-bg @enderror"
                    required
                    autofocus>
            @error('email')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password"
                    name="password"
                    placeholder="••••••••"
                    class="form-input @error('password') incorrect-bg @enderror"
                    required>
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label style="flex-direction: row; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="remember">
                <span style="text-transform: none; letter-spacing: 0; font-size: 0.9rem;">Mantener sesión iniciada</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            Iniciar sesión
        </button>
    </form>
 
    <div style="text-align: center; margin-top: 20px;">
        <p>¿No tienes una cuenta?</p>
        <a href="{{ route('inicio.register.mostrar') }}"><button type="button" class="btn btn-secondary">Registrarse</button></a>
    </div>
@endsection