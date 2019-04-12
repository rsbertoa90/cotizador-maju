<template>
         <nav class="navbar navbar-expand-lg navbar-dark bg-fucsia font-weight-bold">
                <a class="navbar-brand " href="/">
                    <v-lazy-image style="width:100px" src="/storage/images/app/logo.png" alt="Bazar Mayorista Maju" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon text-white"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item text-white">
                            <a class="nav-link text-white" href="/">
                            <span class="fa fa-shopping-cart"></span> Hace tu pedido <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link text-white" href="https://hipermaju.com/contactenos">
                                <span class="fa fa-phone"></span>    Contactate con nosotros
                            </a>
                        </li>
                        <li class="nav-item text-white">
                             <a class="nav-link text-white" target="_blank" href="/MAJU-lista-de-precios.pdf" >
                                <span class="fa fa-download"></span> Descargar lista de precios
                            </a> 
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link text-white" target="_blank" rel="noreferrer"
                                href="/MAJU-catalogo.pdf">
                                <span class="fa fa-download"></span> Descargar catalogo digital
                            </a> 
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link text-white" target="_blank" rel="noreferrer"
                                href="https://goo.gl/maps/41ThzXr52pF2">
                                <span class="fas fa-home"></span> ¿Donde esta nuestro local?
                            </a> 
                        </li>
                    </ul>
                </div>
            </nav>
</template>

<style lang="scss">
    .text-white{
        color:#fff;
       
    }
</style>