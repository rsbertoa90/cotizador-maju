  @extends('pdf.base')

  @section('content')

 
  <main>
      
     

      @foreach ($categories as $category)
      @foreach ($category->products as $p)
            <div style="white-space:normal; word-wrap: break-word;">        
                <pre>
                    {{$p->name}}
                    @if (!$p->images || sizeof($p->images) == 0)
                        <h2>SIN FOTOS</h2>
                    @endif
                    @if ($p->images && sizeof($p->images) > 0)
                        @foreach ($p->images as $i)
                            <pre style="white-space:normal; word-wrap: break-word;">
                                {{$i}}
                            </pre>
                            <br>
                        @endforeach
                    @endif
                </pre> 
            </div>
        @endforeach
          
      @endforeach
  </main>
      
     
  @endsection