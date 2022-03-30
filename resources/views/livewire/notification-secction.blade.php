<div wire:poll >



    <li   class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:void(0);" data-toggle="modal" data-target="#notifications-content" wire:click="actualizar()"><i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i><span class="badge badge-pill badge-danger badge-up">{{count($notifications)}}</span></a>

@if(count($notifications)> 0)

<script>

Push.create("E-cont Dice",{
            body: "Tienes notificaciones pendientes",
            icon: "img/logo.png",
            vibrate: [100, 100, 100],

            onClick: function () {
                window.focus();
                this.close();
        }
    });



     </script>

@endif

</div>
