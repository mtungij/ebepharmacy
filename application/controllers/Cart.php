	<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cart extends CI_Controller{
     function index(){
        $this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $branch_id = (int) $this->session->userdata('branch_id');
        $datay = $this->queries->get_productAll($branch_id ?: null);
        $limit = $this->queries->get_stock_limitData();
        $my = $this->queries->get_mydata($user_id);
        $kwisha = $this->queries->get_bidhaa_kwisha($branch_id ?: null);
          // print_r($product);
          //   exit();
        $data = array();
        // Retrieve cart data from the session
        $cartItems = $this->cart->contents();
        $cart_product_ids = array_column($cartItems, 'id');
        $discount_rules = $this->queries->get_active_discount_rules($branch_id ?: null);
        $product_categories = $this->queries->get_product_category_map($cart_product_ids);
        // print_r($cartItems);
        //      exit();
        $privillage = $this->queries->get_userPrivillage($user_id);
        // Load the cart view
        $this->load->view('seller/cart',['cartItems'=>$cartItems,'datay'=>$datay,'limit'=>$limit,'my'=>$my,'kwisha'=>$kwisha,'privillage'=>$privillage,'discount_rules'=>$discount_rules,'product_categories'=>$product_categories]);
    }

  


      function updateItemQty(){
        $update = 0;
        // Get cart item info
        $rowid = $this->input->get('rowid');
        $item_id = $this->input->get('item_id');
        $qty = $this->input->get('qty');
        //$id = $this->input->get();
          // var_dump($item_id);
              // exit();
        // Update item in the cart
        if(!empty($rowid) && !empty($qty)){
            $data = array(
                'rowid' => $rowid,
                'qty'   => $qty
              );

           if($this->checkForItemBalance($item_id,$qty)){

            $update = $this->cart->update($data);
              echo "ok";
        }else{
          echo "err";
        }


        }
        
        // Return response
        // echo $update?'ok':'err';
    }
    
    function checkForItemBalance($item_id,$qnty){
    $branch_id = (int) $this->session->userdata('branch_id');
    $sql = "SELECT * FROM tbl_store WHERE product_id='$item_id' AND branch_id = '$branch_id' AND balance >= '$qnty'";
      $data = $this->db->query($sql);
      // echo "<pre>";
      // print_r($data->result());
      // echo "</pre>";

      //echo "total  == " . count($data->result());
      if(count($data->result()) > 0){
        return true;
      }
      return false;
    }


   
    
    function removeItem($rowid){
        // Remove item from cart
        $remove = $this->cart->remove($rowid);
        
        // Redirect to the cart page
        redirect('cart/');
    }

        //session destroy
        public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        if (!$this->session->userdata("user_id"))
        return redirect("home/index");
    }


  

    
}
