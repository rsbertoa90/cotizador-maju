  @extends('pdf.base')

  @section('content')

 
  <main>
      
     

      @foreach ($categories as $category)
      
        <pre>
            {{$category}}
        </pre> 
          
      @endforeach
  </main>
      
     
  @endsection