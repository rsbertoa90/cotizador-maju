  @extends('pdf.base')

  @section('content')

  @php
      dd($categories);
  @endphp
  <main>
      
    @if ($categories)
         
   

      @foreach ($categories as $category)
      
         @if (count($category->products)>0)
             
         
          <div style="text-align: center ; width : 780px; text-decoration:underline">
              <h4> {{strtoupper($category->name)}} </h4>
          </div>
                         
          
              <table class="table table-bordered " style="width:800px ; " >

                  <tbody >
                    @php
                        $max = count($category->products) / 4 ;
                    @endphp      
                      @for ($i = 0; $i < $max; $i++)
                            @php
                            $products = $category->products;
                            if (isset($products[$i*4+0])){ $product1 = $category->products[($i*4)+0];} else {$product1 = null;};
                            if (isset($products[$i*4+1])) {   $product2 = $category->products[($i*4)+1];} else {$product2 = null;};
                             if (isset($products[$i*4+2])) {  $product3 = $category->products[($i*4)+2];} else {$product3 = null;};
                             if (isset($products[$i*4+3])) {  $product4 = $category->products[($i*4)+3];} else {$product4 = null;};
                          
                            @endphp
                          <tr>
                              @if ($product1)
                                  
                                    @include('pdf.product',['product'=>$product1])
                              @endif
                              @if ($product2)
                                  
                                @include('pdf.product',['product'=>$product2])
                              @endif
                              @if ($product3)
                                  
                                @include('pdf.product',['product'=>$product3])

                              @endif
                              @if ($product4)
                                  
                                @include('pdf.product',['product'=>$product4])

                              @endif
                            
                          </tr>
                      @endfor
                  </tbody>
              </table>
              <hr>
        @endif
        @endforeach

    @endif
  </main>
      
     
  @endsection