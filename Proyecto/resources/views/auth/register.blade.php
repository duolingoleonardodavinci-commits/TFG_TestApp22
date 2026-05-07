@extends('layouts.app')

@section('title', 'Registro')

@section('content')
    <h1>Crear una cuenta nueva</h1>
    <x-errores />

    <form method="POST" action="{{ route('auth.register') }}" class="form-card">
        @csrf

        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" placeholder="Jane" value="{{ old('nombre') }}" class="form-input @error('nombre') incorrect-bg @enderror" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" placeholder="Doe" value="{{ old('apellidos') }}" class="form-input @error('apellidos') incorrect-bg @enderror" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" placeholder="alumno@email.com" value="{{ old('email') }}" class="form-input @error('email') incorrect-bg @enderror" required>
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" placeholder="••••••••" class="form-input @error('password') incorrect-bg @enderror" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirma contraseña</label>
            <input type="password" name="password_confirmation" placeholder="••••••••" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Registrarse</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <p>¿Ya tienes una cuenta?</p>
        <a href="{{ route('inicio.login.mostrar') }}"><button type="button" class="btn btn-secondary">Iniciar sesión</button></a>
    </div>
@endsection