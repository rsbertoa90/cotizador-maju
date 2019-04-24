<template>
     <transition enter-active-class="animated bounceIn" leave-active-class="animated fadeOutDown">
            <div v-if="total > 0" id="total">
                <div  class="bg-success p-1">
                    <div class=" bg-white d-flex justify-content-center p-1">
                    TOTAL : ${{total | price}}
                    </div>
                </div>
               
                <div  class="bg-success p-1">
                    <div class="bg-white d-flex justify-content-center p-1">
                        <a href="#form"> Terminar pedido </a>
                    </div>
                </div>
            </div>    
    </transition>
</template>

<script>
export default {
    props:['total']
}
</script>

<style lang="scss" scoped>
 #total {
        position: fixed;
        /* margin-left:50vw; */
        bottom: 25px;
        left: 33%;
        z-index: 750;
    }

     @media(min-width: 600px){
        
         #total {
             left:45%;
         }
     }
</style>

