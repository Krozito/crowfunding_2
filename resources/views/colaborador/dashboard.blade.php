<h1>Panel de Colaborador</h1>
<p>Bienvenido {{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>

<form method="POST" action="{{ route('colaborador.logout') }}">
    @csrf
    <button type="submit">Cerrar sesi&oacute;n</button>
</form>
