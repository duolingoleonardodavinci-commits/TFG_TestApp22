<header>
    <a href="/">
        Inicio
    </a>
      
    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
    
</header>