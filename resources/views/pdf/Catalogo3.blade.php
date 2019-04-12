  @extends('pdf.base')

  @section('content')

 
  <main>
      
     

      @foreach ($categories as $category)
      @foreach ($category->products as $p)
            <div style="white-space:normal;">        
                <pre>
                    {{$p}}
                </pre> 
            </div>
        @endforeach
          
      @endforeach
  </main>
      
     
  @endsection