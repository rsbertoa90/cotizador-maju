<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Mail\Cotizacion;
use App\Mail\Aviso;
use App\Order;
use App\Product;
use App\Category;
use App\OrderProduct;
use Mail;
use PDF;
use Carbon\Carbon;
use View;
use App\Jobs\GeneratePricesList;
use App\Jobs\GenerateCatalogo;
use App\Jobs\GenerateCategoryCatalogo;
use Queue;
use App\ProductImage;
class PDFController extends Controller
{
    //

   

    public function pricesList()
    {
        
        $categories = Category::notPaused();
       
        $today = Carbon::now()->format('d/m/Y');

         $html = View::make('pdf.ListaDePrecios',compact('categories','today'))->render();
        
         $pdf = PDF::loadHTML($html);

        return $pdf->download('MAJU-lista-de-precios.pdf');


    }
    
    public function dispatchPricesListJob()
    {


      Queue::push(new GeneratePricesList());

      return redirect('/admin/metadata');
    }

     public function dispatchCatalogoJob()
    {
        Queue::push(new GenerateCatalogo());

        return redirect('/admin/metadata');
    }

    public function dispatchCategoryCatalogJob($id)
    {
        Queue::push(new GenerateCategoryCatalogo($id));

        return redirect('/admin/metadata');
    }


    public function testCatalogo(){
        
       


        $path = public_path().'/MAJU-catalogo.pdf';
        $today = Carbon::now()->format('d/m/Y');
       
        $categories = Category::with('products.images')->whereHas('products', function ($q){
            $q->Has('images')->orderBy('name')->where('paused',0);
        })->has('products.images')->orderBy('name')->get();
        
        foreach ($categories as  $c) {
            foreach ($c->products as $k=>$p) {
                if (!isset($p->images[0]))
                {
                    unset($c->products[$k]);
                    
                }
            }
        }
       /*  foreach ($categories as  $c) {
            foreach ($c->products as $k=>$p) {
                if (!isset($p->images[0]))
                {
                   return $p;
                    
                }
            }
        } */

        $html = View::make('pdf.Catalogo2',compact('categories','today'))->render();

        return $html;
       /*  PDF::loadHTML($html)->save($path); */
    }



    public function catalogo()
    {
        set_time_limit(300);

       $categories = Category::orderBy('name')->get();
        
        $today = Carbon::now()->format('d/m/Y');
        
       /*  return view('pdf.Catalogo',compact('categories','today')); */
        
        $html = View::make('pdf.Catalogo',compact('categories','today'))->render();
        
        $pdf = PDF::loadHTML($html);

        return $pdf->download('catalogo.pdf');

    }


    public function replaceCatalogo(Request $request)
    {
        /* comments */
        $catalogo = $request->file('catalogo');

        $path = public_path().'/MAJU-catalogo.pdf';

        move_uploaded_file($catalogo,$path);

        return redirect('/admin/metadata');
    }
}
