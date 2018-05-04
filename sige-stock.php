<?php
/*
* Class that integrates with SIGE Cloud
*/

class SIGE_Stock
{
  private $api;

  public function __construct()
  {
      $this->api = curl_init();

      $header = array();
      $header[] = 'Authorization-Token: b2425ef0abf105a336468ea6cfcdfc6da14505dbf43466ca28a760132b65f441680889f9122aa486e7b97b2160903b4c5cabe2adcb317e0d4303e1225c4d792bbbb748b5975052f698a74b178abf1259847389915fe22733c4929eeac29b7efb8433a52a0ee66d8edb9d246578672f8e818ea8458e8944b58457299aa15eb863';
      $header[] = 'User: contato@floraleve.com.br';
      $header[] = 'App: floraleveAPI';

      curl_setopt($this->api, CURLOPT_HTTPHEADER, $header);
      curl_setopt($this->api, CURLOPT_RETURNTRANSFER, 1);
  }

  function set_url($code)
  {
      curl_setopt($this->api, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/get?codigo=" . $code);
  }

  public function run_sige($code)
  {

    $this->set_url($code);

    $rest = curl_exec($this->api);
    $dec = json_decode($rest, true);

    $code = sanitize_text_field($dec["Codigo"]);
    $stock = sanitize_text_field($dec["EstoqueSaldo"]);
    $price = sanitize_text_field($dec["PrecoVenda"]);
    $name = sanitize_text_field($dec["Nome"]);
    $visivel = sanitize_text_field($dec["Pratileira"]);

    if(null === $stock)
    {
      $stock = 0;
    }

    //var_dump($dec);

    // Find the Woocommerced product
    $productID = wc_get_product_id_by_sku($code);
    $product = wc_get_product($productID);

    print_r("<br />");
    print_r("Verificando ID: " . $code);
    print_r("<br />");

    if(false === $product)
    {
      if("N" === $visivel){
        return;
      }

      $this->add_product($code, $name, $stock, $price);

    } else {
      if("N" === $visivel){
          $product->delete();
          print_r("<b>Produto excluído!</b>");
          print_r("<br />Produto: " . $product->get_name($view));
          print_r("<br />");
          return;
      }

      $this->update_product($product, $stock, $price);

    }

    //curl_close($this->api);
  }

  public function add_product($code, $name, $stock, $price)
  {

    $post_id = wp_insert_post( array(
      'post_title' => $name,
      'post_status' => 'publish',
      'post_type' => "product",
    ) );
    $product = wc_get_product($post_id);

    if(false === $product){
        return;
    }

    $product->set_sku($code);
    $product->set_manage_stock(true);
    $product->set_stock_quantity($stock);
    $product->set_backorders('no');
    $product->set_regular_price($price);
    $product->save();

    print_r("<b>Produto criado!</b>");
    print_r("<br />Produto: " . $product->get_name($view));
    print_r("<br />Preço: " . $product->get_price($view));
    print_r("<br />Estoque: " . $product->get_stock_quantity($view));
    print_r("<br />");

  }

  public function update_product($product, $stock, $price)
  {
      // Update woocommerce data
      $product->set_manage_stock(true);
      $product->set_stock_quantity($stock);
      $product->set_backorders('no');
      $product->set_regular_price($price);
      // $product->set_price($price); //active price
      $product->save();

      //Show the updated data
      print_r("<b>Dados atualizados</b>");
      print_r("<br />Produto: " . $product->get_name($view));
      print_r("<br />Preço: " . $product->get_price($view));
      print_r("<br />Estoque: " . $product->get_stock_quantity($view));
      print_r("<br />");

  }

}
