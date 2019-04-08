<template>
<div>

    <table class="table table-striped table-bordered" >
            <thead class="">
                <th>Foto</th>
                <th v-if="user && user.role_id < 3">Codigo</th>
                <th class="nametd">Nombre</th>
                <th colspan="2" class="text-center">Precio</th>
                <!-- <th  class="">Llevando mas de</th> -->
                <th class="">Quiero</th>
                <th v-if="$mq == 'lg'" class="">Subtotal</th>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id" >
                    <td width="10%" @click="show(product)"> 
                        <v-lazy-image v-if="product.images && product.images.length > 0" 
                                    class="sampleImage" 
                                    :src="product.images[0].url" 
                                    :alt="product.name" />
                        <v-lazy-image v-else :alt="product.name" 
                                    src="/storage/images/app/no-image.png" /> 
                                    
                    </td>
                    <td v-if="user && user.role_id < 3"> {{product.code}} </td>
                    <td style="cursor:pointer" @click="show(product)">  {{product.name | ucFirst}} </td>
                    <td class="text-info text-center"> 
                        <span v-if="product.price > 0"> ${{product.price | price}} </span>
                        <span v-else> - </span> 
                        <br>
                        <div v-if="product.pck_units > 1 && product.price > 0 ">
                            <span> - de {{product.pck_units}}  </span>
                            <br>
                            <span>Unidades</span>
                        </div>
                    </td>
                <!--     <td class="text-center">
                        <span v-if="product.pck_units > 1"> {{product.pck_units}} </span>
                        <span v-else> Venta x unidad </span>
                    </td> -->
                    <td  class="text-center text-success font-weight-bold"> 
                        <span v-if="product.pck_units > 1"> ${{product.pck_price | price}} </span>
                        <span v-else> - </span> <br>
                        <div v-if="product.pck_units > 1">
                            <span> + de {{product.pck_units}}  </span>
                            <br>
                            <span>Unidades</span>
                        </div>
                    </td>

                    <td><input type="number" min="0" class="form-control " v-model="product.units">
                        
                        <div v-if="$mq != 'lg' && product.units > 0" class="text-success d-flex flex-column p-0 m-0 justify-content-center align-items-center">
                            
                            <span v-if="product.units < product.pck_units">  ${{(product.price * product.units) | price}} </span>
                            <span v-if="product.units >= product.pck_units">  ${{(product.pck_price * product.units) | price}} </span>
                            
                        </div>
                    
                    </td>
                    
                    <td v-if="! product.units && $mq == 'lg'"> 0 </td>
                    <td v-else-if="product.units < product.pck_units & $mq == 'lg'">$ {{ (product.units * product.price).toFixed(2) }}  </td>
                    <td v-else-if="$mq == 'lg'"> ${{ (product.units * product.pck_price).toFixed(2) }} </td>
                </tr>
            </tbody>
    </table>
    <image-modal @close="closedModal" v-if="this.showModal"
                    :product="modalProduct"  ref="modal" ></image-modal>
</div>
</template>

<script>
import imageModal from './Img-modal.vue';
export default {
    props:['products'],
    components:{imageModal},
    data(){return{
         showModal : true,
         modalProduct:null,
    }},
    computed:{
        user(){
            return this.$store.getters.getUser;
        },
    },
    methods:{
          show(product){
               this.showModal = true;
               this.modalProduct = product;
               /* this.$refs.modal.$forceUpdate(); */
               
               let element = this.$refs.modal.$el;
               $(element).modal('show');
        },
        closedModal(){
                 this.modalProduct = null;
                 this.showModal = false;
                setTimeout(() => {
                    this.showModal=true;
                }, 100);
        },
    }
}
</script>


<style lang="scss" scoped>
 
  

    .card-header{
        padding: 5px;
        display: flex;
        align-items:center;
    }


.sampleImage{
        width: 50px;
    }

    .btn-link {color : black;}


    img{width:100%}

     @media(max-width: 600px){
        
     
        


        td { white-space : normal;}
        #accordion{
            margin: 0 -3%;
        }
        table {
            table-layout: fixed;
            width:95vw;
            font-size: 0.66rem;
            font-weight: bold;
        }
        input[type="number"]{
            max-width: 70px;
        }
       
        .card-body,table th, table td{padding:5px;}
    }
    
    @media(min-width: 600px){
        
      
         .sampleImage{
            width: 150px;
        }
        table{ font-size: 1rem; font-weight: normal}
        td {white-space: normal;}
        .card-body,.container{padding:1.25rem}
        
    }

    .big{
    font-size: 1.7rem;
    white-space: normal;
    padding: 0;
}


</style>
