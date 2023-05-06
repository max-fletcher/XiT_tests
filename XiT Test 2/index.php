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
set_time_limit(0);
ini_set('max_execution_time', 0);

$url = 'https://yourpetpa.com.au';
$array = [];
$index = 1;

$client = new \GuzzleHttp\Client();
try {
   $response = $client->request('GET', $url);
} catch (\Exception $e) {
   echo $e->getMessage();
}

$html = $response->getBody();
// echo $html;

$crawler = new Crawler($html);

$nodeValues = $crawler->filter('li.mega-dropdown__item')->each(function(Crawler $node, $i)use(&$url, &$client, &$index, &$array){

      $category = '';

      $node->filter('a.site-nav__dropdown-heading')
            ->each(function(Crawler $node, $i)use(&$url, &$category, &$client, &$index, &$array){
                  $category = $node->text();
                  $link = $node->attr('href');

                  if(!str_contains($link, $url)){
                     $link = $url . $link;
                  }

                  // echo $category;
                  // echo '<br> 1stloop <br>';
                  // echo $link;
                  // echo '<br><br>';

                  try {
                     $response = $client->request('GET', $link);
                  } catch (\Exception $e) {
                     echo $e->getMessage();
                  }

                  $category_html = $response->getBody();

                  $crawler = new Crawler($category_html);
                  $nodeValues2 = $crawler->filter('a.product-block__image')
                        ->each(function(Crawler $node, $i)use(&$url, &$category, &$client, &$index, &$array){
                              $product_page_link = $node->attr('href');

                              if(!str_contains($product_page_link, $url)){
                                 $product_page_link = $url . $product_page_link;
                              }

                              echo $product_page_link;
                              echo '<br>';

                              try {
                                 $response = $client->request('GET', $product_page_link);
                              } catch (\Exception $e) {
                                 echo $e->getMessage();
                              }

                              $product_html = $response->getBody();

                              // echo $product_html;

                              $crawler = new Crawler($product_html);

                              // print_r($crawler->filter('h3.product-detail__title')->first()->text());

                              $product_title = $crawler->filter('h3.product-detail__title')->first()->text();
                              $product_description = '';
                              $product_price = $crawler->filter('span.product-price__reduced > span.theme-money')->text('no_value');
                              if($product_price == 'novalue'){
                                 $product_price = $crawler->filter('span.theme-money')->last()->text();
                              }
                              $image_url = $crawler->filter('img.rimage__image')->first()->attr('data-srcset');

                              // echo $index;
                              // echo $product_title;
                              // // echo $product_description;
                              // echo $category;
                              echo $product_price;
                              // echo $product_page_link;
                              // echo $image_url;
                              $index++;
                              echo '<br><br>';

                              array_push($array,
                                 [
                                    'ID' => $index,
                                    'Title' => $product_title,
                                    'Description' => $product_description,
                                    'Category' => $category,
                                    'Price' => $product_price,
                                    'URL' => $product_page_link,
                                    'ImageURL' => $image_url
                                 ]
                              );
                        });
            });
});

// print_r($array);

$file = fopen('products.csv', '2');
foreach($array as $item){
   fputcsv($file, $item);
}
fclose($file);

echo 'EXPORTED';

