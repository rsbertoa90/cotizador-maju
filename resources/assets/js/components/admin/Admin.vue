<template>
    <div class="container">   
          <div v-if="loading" class="loader">
            <dot-loader :loading="loading" size="200px"></dot-loader>
         </div> 
         <div v-if="$mq == 'sm'" class="w-100 d-flex flex-column align-items-center jusify-content-center">
            <h2 class="text-warning">
                Lo sentimos. El administrador no esta disponible en dispositivos moviles.
            </h2>
        </div>

        <div v-else class="row">
            
             <div class="col-12 d-flex justify-content-center">
                 <img src="/storage/images/app/MAJU.jpg" style="width : 200px ; height: 110px" alt="logo">
             </div>
             <hr>
             <div class="col-12">
                <admin-create :supliers="supliers" :categories="categories" @productSaved="refresh"></admin-create>
             </div>
             <hr>
            <!-- <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                <change-prices :selectedProducts="selectedProducts"
                            @refresh="refresh()" @resetCheckboxes="resetCheckboxes()"></change-prices>
            </div> -->
            <hr class="w-100">
            
            <div class="col-12 row">
                    <div class="col-6 row">
                        <label class="text-info font-weight-bold col-4">Ordenar por</label>
                        <select class="form-control col-6" v-model="orderBy" id="">
                            <option value="suplier.name">Proveedor</option>
                            <option value="category.name">Categoria</option>
                            <option value="name">Producto</option>
                            <option value="price">Precio</option>
                        </select>
                    </div>
                    <div  class="col-6 d-flex flex-column">
                        <div class="d-flex align-items-center ">
                             <label class="text-info font-weight-bold">Filtrar</label>
                            <input type="checkbox" class="form-control" v-model="selector.checked" @change="checkSelect">
                            <select class="form-control col-4"  v-model="selector.id">
                                <option value="all"> Todo</option>
                                <option v-if="orderBy == 'category.name'" 
                                        v-for="category in categories" :key="category.id" :value="category.id"> 
                                        {{category.name}}
                                </option>
                                <option v-if="orderBy == 'suplier.name'" 
                                        v-for="suplier in supliers" :key="suplier.id" :value="suplier.id"> 
                                        {{suplier.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 row mt-3 mb-3 ml-2">
                    <input v-model.lazy="searchTerm" placeholder="BUSCAR" @keyup.enter="search" class="form-control col-4">
                    <button class="btn btn-outline-success ml-2" @click="search">Buscar</button>
                </div>
                
                <div class="row mt-4 ml-2">
                  <paginator :selectedPage="selectedPage"   
                            :products="filterProducts" 
                            :productsPerPage="productsPerPage"
                            @selectPage="selectedPage=$event">

                </paginator>
                </div>
                <table id="table" class="table table-striped table-bordered ">
                                <thead class="">
                                    <th>imagen</th>
                                    <th>Codigo</th>
                                    <th>Proveedor</th>
                                    <th>Categoria</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th >Unidades x bulto</th>
                                    <th >Precio en bulto</th>
                                </thead>
                                <!-- <transition-group tag="tbody" 
                                                    enter-active-class="animated slideInLeft faster "
                                                    leave-active-class="animated slideOutRight faster position-absolute ">
                                 -->   <tr is="product-row" v-for="product in filteredProducts" 
                                                :key="product.id" 
                                                :product="product"
                                                :supliers="supliers"
                                                :categories="categories"
                                                @refresh="refresh"
                                                >
                                    </tr>
                              <!--   </transition-group> -->
                </table>
                <paginator :selectedPage="selectedPage"   
                            :products="filterProducts" 
                            :productsPerPage="productsPerPage"
                            @selectPage="selectedPage=$event">

                </paginator>
                          
               
                
                  
                 
        </div>

         
    </div>
</template>

<script>

import adminCreate from './admin/Create.vue';
import changePrices from './admin/Change-prices.vue';
/* import adminReport from './Report.vue'; */
import productRow from './admin/Product-row.vue';
import paginator from './admin/paginator.vue';
    export default {
        components : {
            paginator,
            changePrices,
           
            adminCreate,
            productRow
        },
        data(){
            return {
                loading:true,
                searchMode:false,
                searchTerm:'',
                selectedPage:1,
                productsPerPage:30,
                selector : {id :'all', checked : false},
                variation:0,
                products : [],
                categories :[],
                list : [],
                supliers : [],
               
                showModal : false,
                orderBy : 'suplier.name'
            }
        },
        computed:{
            selectedProducts()
            {
                var list =[];
                    this.products.forEach(prod => {
                        if (prod.selected)
                        {
                            list.push(prod);
                        }
                    });
                return list;
            },
         
            filterProducts(){
                if (this.searchMode){
                    return this.searchFilter();
                }
                else {
                    if (this.products.length > 0)
                    {
                        var prop = null;
                        if (this.orderBy == 'category.name'){prop = 'category'}
                        else if (this.orderBy == 'suplier.name'){prop = 'suplier'}
                        
                        if (prop && this.selector.id != 'all'){
                            var filtered = this.products.filter(prod => {
                                    return prod[prop].id == this.selector.id;     
                            });
                            return _.orderBy(filtered,this.orderBy)
                        } else{ return _.orderBy(this.products,this.orderBy) }
                    }
                    return [];
                }
            },
            filteredProducts()
            {
              let prods = this.filterProducts;
      
              return this.paginate(prods,this.selectedPage);  
            }
        },
         watch : {
            orderBy(){
                this.products = _.sortBy(this.products,this.orderBy);
                this.selector.id ='all';
                this.resetFilters();
            },
            'selector.id'()
            {
                this.resetFilters();
            }


        },
        methods : {
            
            resetFilters(){
                this.resetCheckboxes();
                this.selectedPage = 1;
                this.searchMode=false;
                this.searchTerm='';
            },
            search(){
                this.loading=true;
                let term = this.searchTerm;
                this.selector.id = 'all';
                this.selectedPage = 1;
                setTimeout(() => {
                    this.searchTerm = term;
                    this.searchMode = true;
                    this.loading=false;
                }, 100);
                this.searchMode = true;  
            },
            searchComparision(term,prod){
                  let prodName = prod.name.toLowerCase().trim();
                  term = term.toLowerCase().trim();
                  let categoryName = prod.category.name.toLowerCase().trim();
                  let suplierName = prod.suplier.name.toLowerCase().trim();
                  let code = prod.code.toLowerCase().trim();

                  if (
                      prodName.indexOf(term) > -1
                      || categoryName.indexOf(term) > -1
                      || suplierName.indexOf(term) > -1
                      || code.indexOf(term) > -1
                  ){return true;}
                  else{return false;}
            },
            searchFilter(){
                this.loading=true;
                let terms = this.searchTerm.split(' ');
                let res = [];
                this.products.forEach(prod => {
                    let include = true;
                    terms.forEach(term => {
                        if (include && !this.searchComparision(term,prod))
                        {
                            include = false;
                        }
                    });
                    if (include){
                        res.push(prod);
                    }
                });
                this.loading=false;
                return res;

                
                
            },
            paginate(array,page){
                if(array && array.length>0){
                    page--; 
                   return array.slice(page * this.productsPerPage, (page + 1) * this.productsPerPage);
                }
            },

           
            resetCheckboxes(){
                this.selector.checked =false;
                this.products.forEach(prod => {
                    if (prod.selected == undefined)
                    {
                        Vue.set(prod,'selected',false);
                    }else {

                        prod.selected = false;
                    }
                });
            },
            checkSelect(){
                if (this.selector.id == 'all')
                {
                    this.products.forEach(prod => {
                        if (prod.selected == undefined)
                        {
                            Vue.set(prod,'selected',true);
                        }
                        prod.selected = this.selector.checked;
                    });
                }else{
                    if (this.orderBy == 'category.name')
                    {
                        this.products.forEach(prod => {
                            if (prod.category.id == this.selector.id)
                            {
                                if (prod.selected == undefined)
                                {
                                    Vue.set(prod,'selected',true);
                                }
                                prod.selected = this.selector.checked;
                            }
                        });
                    } else if (this.orderBy == 'suplier.name')
                    {
                         this.products.forEach(prod => {
                            if (prod.suplier.id == this.selector.id)
                            {
                                if (prod.selected == undefined)
                                {
                                    Vue.set(prod,'selected',true);
                                }
                                prod.selected = this.selector.checked;
                            }
                        });
                    }
                }


            },
           
          
         
         
            refresh(){
                var vm = this;
                $.ajax({
                    url : 'api/categories',
                    success(response){
                        vm.categories = response;
                        vm.categories = _.sortBy(vm.categories,'name');
                    }
                });
                $.ajax({
                    url : 'api/supliers',
                    success(response){
                        vm.supliers = response;
                         vm.supliers = _.sortBy(vm.supliers,'name');
                    }
                });
                $.ajax({
                url : 'api/products',
                success(response){
                    vm.products = response;
                    vm.products = _.sortBy(vm.products, vm.orderBy);
                    vm.loading=false;
                    
                }
            });
            },
            saveChange(product,field){
                var data = {
                    product : product.id,
                    field : field,
                    value : product[field]
                }
               
                $.ajax({
                    method : 'put',
                    data : data,
                    url : '/admin/product/update'
                });
            },
          
               selectAllProducts()
            {
                    this.products.forEach(prod => {
                        if (prod.selected == undefined)
                        {
                            Vue.set(prod,'selected',true)
                        }
                        else {
                            prod.selected = true;
                        }
                    });
            },
         
        },
        created(){
            this.refresh();
        },
        mounted() {
              this.$http.get('/getuser')
                .then(response => {
                    this.user = response.data;
                });
        },
    }
</script>

<style scoped>

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


input[type="checkbox"]{
    width: 25px;
    margin:  10px;
    height: 20px;
}


.smallField{width: 80px;}
td {min-width: 120px;}
.btn-link {color : black;}
    td img {
        width: 10vw;
    }
   @media(max-width: 600px){
       table,.container,.card,.card-body {font-size: 0.66rem ; padding : 0}
       
       th,td, input{
           white-space:nowrap;
           width: 13vw;
           padding: 2px;
       }
       
       
        
       .nametd {width: 25vw;}
   }


</style>
