<template>
    <div class="">
        <div v-if="loading" class="loader">
            <dot-loader :loading="loading" size="200px"></dot-loader>
        </div>

        <h5>Envianos tu pedido</h5>
        <p>Nos estaremos comunicando para confirmar tu presupuesto</p>
        <form class="col-12" id="form">
                <csrf></csrf>

              <div class="col-12 row form-group-row mb-3">
                  <div class="col-12 col-lg-4">
                    <input  type="radio"
                           v-model="formData.shipping" :value="false"> 
                     <span @click="formData.shipping=false"  class="text-secondary radioText" :class="{'text-success':!formData.shipping}">
                        <span class="fa fa-building"></span> Retiro en el local
                     </span>
                  </div>
              </div>
              <div class="col-12 row form-group-row mb-3">
                  <div class="col-12 col-lg-4">
                    <input  type="radio"
                           v-model="formData.shipping" :value="true"> 
                    <span  @click="formData.shipping=true" class="text-secondary radioText" :class="{'text-success':formData.shipping}">
                        <span class="fa fa-truck"></span> Envio por transporte
                    </span>
                  </div>
               </div> 

                

               <div class="col-12 row form-group-row">
                   <label class="col-4 col-lg-2" for="">
                       Nombre y Apellido 
                      
                    </label>
                   <input required type="text" v-model="formData.name"  class="form-control col-8 col-lg-4">
                </div>

                <!-- DATOS DE ENVIO -->
                <div v-if="formData.shipping">
                    
                    <div class="col-12 row form-group-row">
                        <label class="col-4 col-lg-2" for=""> Provincia </label>
                        <select v-if="states.length > 0" v-model="state" class="form-control col-8 col-lg-4">
                            <option v-for="opt in states" :key="opt.id" :value="opt"> 
                                {{opt.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 row form-group-row">
                        <label class="col-4 col-lg-2" for=""> Ciudad </label>
                        <select v-if="state" v-model="formData.city" class="form-control col-8 col-lg-4">
                            <option v-for="opt in state.cities" :key="opt.id" :value="opt.id"> 
                                {{opt.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-12 row form-group-row">
                        <label class="col-4 col-lg-2" for=""> Direccion </label>
                        <input  type="text" v-model="formData.address"  class="form-control col-8 col-lg-4">
                    </div>
                    <div class="col-12 row form-group-row">
                        <label class="col-4 col-lg-2" for=""> Transporte </label>
                        <input  type="text" v-model="formData.transport"  class="form-control col-8 col-lg-4">
                    </div>
                    <div class="col-12 row form-group-row">
                        <label class="col-4 col-lg-2" for=""> Codigo Postal </label>
                        <input  type="text" v-model="formData.cp"  class="form-control col-8 col-lg-4">
                    </div>
                </div>
                <!-- /DATOS DE ENVIO -->

               <div class="col-12 row form-group-row">
                   <label class="col-4 col-lg-2" for="">Email</label>
                   <input required type="email" v-model="formData.email"  class="form-control col-8 col-lg-4">
                </div> 
               <div class="col-12 row form-group-row">
                   <label class="col-4 col-lg-2" for="">Telefono</label>
                   <input v-model="formData.phone" type="text" class="form-control col-8 col-lg-4">
                </div> 
               <div class="col-12 row form-group-row">
                   <label class="col-4 col-lg-2" for="">Mensaje</label>
                   <textarea v-model="formData.message" name="msg" class="form-control col-8 col-lg-4"></textarea>
                </div> 
                <button class="button  btn-lg btn-outline-success offset-2 mt-2" @click.prevent.stop="send" style="z-index:901;">Enviar</button>

        </form>
            
    </div>
</template>

<script>
export default{
    props : {
        list : {default : []},
        total : {default : 0},
       
    },
    computed:{
        states(){
            return this.$store.getters.getStates;
        },
        configs(){
            return this.$store.getters.getConfig;
        },
       user(){
           return this.$store.getters.getUser;
       }
    },

    data(){return{
       
      
        state:null,
        cities:[],
        loading : false,
        formData : {
            name : '',
            seller:'',
            message : '',
            phone : '',
            email : '',
            shipping: true,
            city: null,
            address:'',
            transport:'',
            cp:'',

        }
    }},

    methods : {
         minBuy(){
             if(this.configs){
                 if (this.formData.shipping){
                     return this.configs.minbuy_ship;
                 }
                 else {return this.configs.minbuy;}
             }
        },
        validateEmail(email) {
      
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
       
            return re.test(String(email).toLowerCase());
        },
        formValid()
        {   
            if (this.formData.shipping && !this.formData.city)
            {
                  swal('Por favor selecciones una localidad ','','error');
                 return false; 
            }
            else if(!this.formData.name || this.formData.name.trim() == '')
            {
                 swal('El campo "Nombre y Apellido" es obligatorio ','','error');
                 return false; 
            }
            else if (!this.validateEmail(this.formData.email)){
                swal('Hay algo mal con el mail','','error');
                return false;
            }
            else if (this.formData.email.length < 4  )
            {
                swal('Hay algo mal con el mail','','error');
                return false;
            }else if (this.list.length <= 0) 
            {   
                swal('No hay productos seleccionados','','error');
                return false;
            } else if (this.total < this.minBuy())
            {
                swal('El minimo de compra es de $'+this.minBuy(),'','error');
                return false;
            } else {return true;}
        },
        compressList(){
            let res = [];
            this.list.forEach(item => {
                let compresedItem = {
                    id : item.id,
                    units: item.units
                }
                res.push(compresedItem);
            });
            
            return res;

        },
        send(){
            if (this.formValid()){

                var data = this.formData;
                if (data.shipping)
                {
                    data.shipping = 1;
                } else {data.shipping = 0;}

                let list = this.compressList();


                data.list = JSON.stringify(list);
                data.total = this.total;
                
                var vm = this;
                vm.loading = true;
               
   

                    $.ajax({
                        method : 'post',
                        data : data,
                        url : '/cotizer/send',
                        success(){
                            vm.loading = false;
                            swal('Enviamos tu presupuesto', 'Te estaremos contactando a la brevedad', 'success')
                                .then(() => {
                                    window.location.replace('/');
                                });
                        } 
                    });
                
            }
        }
    },
   
    
}

</script>

<style lang="scss" scoped>
.loader {
    position : fixed;
    height: 100%;
    width: 100%;
    z-index: 110;
    background-color: #ddddddaa;
    left: 0;
    top: 0;
    display: flex;
    justify-content: center;
    align-items: start;
    padding-top: 5%;
}

input[type="radio"] {
  -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
  -moz-appearance: checkbox;    /* Firefox */
  -ms-appearance: checkbox;     /* not currently supported */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
  margin-right: 15px;
}
.radioText{
    font-size:1.2rem;
}
.button{
    z-index:850;
    position:relative;
    background-color: #fff;
}
</style>