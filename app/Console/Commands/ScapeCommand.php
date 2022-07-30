<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HotelDetail;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
class ScapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $url = 'https://www.vietnambooking.com/combo/combo-honeymoon-da-lat-hotel-colline-3n2d-bua-toi-ve-may-bay.html';
        // // $client = new Client(HttpClient::create(['verify_peer' => false]));
        // $client = new Client();
        // $all_image = [];
        // $crawler = $client->request('GET', $url);
        // $crawler->filter('.box-item-img.fancybox-thumb')->each(
        //     function(Crawler $node) use (&$all_image){
        //         $image = $node->filter('img')->attr('src');
        //         if($image != null){
        //            $all_image[] = $image;
        //         }
        //     }
        // );
        $all_image = json_encode([
            "https://cdn2.vietnambooking.com/wp-content/uploads/hotel_pro/hotel_302454/de76259f38c5437b0e67e40a7343ef6d.jpg",
            "https://cdn2.vietnambooking.com/wp-content/uploads/hotel_pro/hotel_302454/2342ac3d49b5581707583e3de7436f57.jpg",
            "https://cdn2.vietnambooking.com/wp-content/uploads/hotel_pro/hotel_302454/b8630f0be3f0810d7afd3771cd53257d.jpg",
            "https://cdn2.vietnambooking.com/wp-content/uploads/hotel_pro/hotel_302454/8ad2093068599f7b20c47ff292cd1606.jpg"

        ]);
        for($i = 1; $i <= 20; $i++){
            $hotel_detail = new HotelDetail;
            $hotel_detail->hotel_id = $i;
            $hotel_detail->service = json_encode(["restaurant", "park", "pool"]);
            $hotel_detail->thumbnail =json_encode($all_image);
            $hotel_detail->save();
        }
    }
}
