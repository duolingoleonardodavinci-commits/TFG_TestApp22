<header>
    <a href="/">
        <button type="button">Inicio</button>
    </a>
      
    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
    
</header>