<div>
    {{-- JS --}}
    <script src="{{ asset('js/tareas.js') }}" defer></script>

    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Condicional para saber si es administrador o usuario --}}
                    @if (!empty(auth()->user()->admin))
                        {{-- Seccion de administrador --}}
                        <livewire:tareasadmin />
                    @else
                        {{-- Seccion de usuario --}}
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>
