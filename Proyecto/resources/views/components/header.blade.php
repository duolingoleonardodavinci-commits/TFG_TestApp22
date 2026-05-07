<header class="main-header">
    <a href="/">
        <button type="button" class="btn btn-secondary">Inicio</button>
    </a>
      
    <form action="{{ route('auth.logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn btn-danger">Cerrar sesión</button>
    </form>
</header>