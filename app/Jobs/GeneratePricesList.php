<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
/* use Illuminate\Contracts\Bus\SelfHandling; */

use App\Category;
use Carbon\Carbon;
use PDF;
use View;
use App\Metadata;

class GeneratePricesList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    
    public $tries = 1;
    public $timeout = 3600;

   /*  public $tries =2;
    public $timeout = 3600; */
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = public_path().'/MAJU-lista-de-precios.pdf';

        $today = Carbon::now()->format('d/m/Y');
        $categories = Category::with('products.images')
                    ->with(['products' => function($q){
                        $q->where('paused',0);
                    }])
                    ->whereHas('products' , function($q){
                $q->where('paused',0)->orderBy('name');
            })->orderby('name')->get();

        $html = View::make('pdf.ListaDePrecios',compact('categories','today'))->render();

        PDF::loadHTML($html)->save($path);

    }

/*     public function failed(){
      Metadata::create([
          'page' => 'job failed',
          'metatitle' => 'faile',
          'metadescription'=> 'fail!!'
      ]);
    } */
}
