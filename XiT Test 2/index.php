<?php

require 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

// // URL

// // $term = 'cat';
// // $url = 'https://www.pinterest.com/search/pins/?q=' . $term . '&rs=typed';
// $url = 'https://yourpetpa.com.au/';
// $url .= '/collections/prescription-medication-script-required?page=1';

// // GO GET THE DATA FROM THE URL
// $client = new \GuzzleHttp\Client();
// try {
//    $response = $client->request('GET', $url);
// } catch (\Exception $e) {
//    echo $e->getMessage();
// }

// $html = $response->getBody();
// // echo $html;

// $crawler = new Crawler($html);

// // LOOP FOR THE DATA

// // $crawler->filter('body');

// // print_r($crawler);
// $nodeValues = $crawler->filter('.product-block__title > a')->each(function (Crawler $node, $i){
//    // SEARCH FOR THE VALUES THAT I WANT
//    echo $node->attr('href');
// });

// // print_r($nodeValues);

// // ECHO BACK OUT TO THE SCREEN




// REAL WORK
$url = 'https://yourpetpa.com.au';

$categories = ['dog', 'cat', 'shop-other', 'prescription-medication-script-required'];

$products_pages_url = 'https://yourpetpa.com.au/collections/prescription-medication-script-required';
$paginated_products_pages_url = '';
// $i = 1;

for($i = 1; $i <= 2; $i++){

   $paginated_products_pages_url = $products_pages_url . '?page=' . $i;

   $client = new \GuzzleHttp\Client();
   try {
      $response = $client->request('GET', $paginated_products_pages_url);
   } catch (\Exception $e) {
      echo $e->getMessage();
   }

   $html = $response->getBody();
   // echo $html;

   $crawler = new Crawler($html);

   $nodeValues = $crawler->filter('.product-block__title > a')->each(function(Crawler $node, $i)use($url, $client){
      // SEARCH FOR THE VALUES THAT I WANT
      // echo $node->text();
      // echo $node->attr('href');
      // echo '<br>';

      $node_link = $node->attr('href');
      $single_product_url = $url . $node_link;
      // echo $node_link;
      // echo '<br>';
      // echo $single_product_url;
      // echo '<br>';

      try {
         $response = $client->request('GET', $single_product_url);
      } catch (\Exception $e) {
         echo $e->getMessage();
      }

      $html = $response->getBody();
      $crawler = new Crawler($html);

      $title = $crawler->filter('.product-detail__title')->text();
      $price = $crawler->filter('.product-detail__price > span > .theme-money')->text();

      echo $title;
      echo $price;

   });

   echo $i;
};





