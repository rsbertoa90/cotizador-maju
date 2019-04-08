<template >
    <div  ref="modal" class="modal fade" id="image-modal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
    <div  v-if="product" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> {{product.name}} </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"  >
          <div class="image-container">
            <transition  leave-active-class=" animated slideOutRight faster position-absolute">
            
               <img v-if="product.images && product.images.length > 0" 
                        :key="product.images[i].id"
                        class="w-100 " 
                        :src="product.images[i].url" 
                        :alt="product.name">

                
               
            </transition>  
                <img v-if="!product.images || ! product.images.length > 0" src="/storage/images/app/no-image.png" :alt="product.name">

            <div class="controls"  v-if="product.images && product.images.length > 1" >
                <span  style="cursor-pointer" @click="changeImage('prev')" >  <i class="fa fa-chevron-left text-info"></i> </span>
                <span style="cursor-pointer"  @click="changeImage('next')" >  <i class="fa fa-chevron-right text-info"></i> </span>
            </div>

          <!--   <form v-if="product.images && product.images.length > 0"  action="/admin/product/deleteImage" method="POST">
                <input type="hidden" name="_token" :value="csrf">
                <input type="hidden" name="id"  :value="product.images[i].id">
                <button type="submit" class="close-button btn btn-danger btn-sm">X</button>
            </form> -->

          </div>
         <!--  <form enctype="multipart/form-data" name="uploader" >
          <csrf></csrf>
            <div class="d-flex flex-column">
                <label class="text-info text-center">  
                    Subir una imagen   
                </label>
                 <input type="file" name="file"  accept="image/x-png,image/gif,image/jpeg" class="display-none" >
            </div>   
           
          </form> -->
       
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click="save">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div> -->
    </div>
  </div>
</div>
</template>

<script>
    export default {
        
        props: ['product'],
        data: function(){
            return {
               file : null,
               i:0,
               csrf:csrf,
               show:true
            }
        },
       
        watch:{
            product(){
                this.show=true;
                this.preloadImages();
            }
        },
        methods : {
            preloadImages(){
                /* console.log('preload'); */
                let images = [];
                this.product.images.forEach(image => {
                    let img = new Image();
                    img.src = image.url;
                    images.push(img);
                   /*  console.log(img); */
                });
            },
            close(){
                this.i = 0;
                this.$emit('close');
            },

          

        changeImage(where){
            if (where == 'next'){
                this.i++;
                if (! this.product.images[this.i]){
                    this.i = 0;
                }
            } else{
                this.i--;
                if (this.i < 0){
                        this.i = this.product.images.length - 1
                    }
                }
        }
            
        
    },

  
}
</script>

<style lang="scss" scoped>

    .image-container{
        position:relative;
        overflow: hidden;
    }

    .close-button{
        position:absolute;
        bottom:10px;
        left:50%;

    }

    .controls{
        position: absolute;
        bottom: 50%;
        left:0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        .fa{
            font-size:2rem;
            cursor: pointer;
        }

    }

</style>

