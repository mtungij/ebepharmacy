	<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_cart extends CI_Controller{
     private function current_admin_branch_id(){
        $session_branch_id = $this->session->userdata('admin_branch_id');
        return $session_branch_id ? (int) $session_branch_id : null;
     }

     function index(){
        $this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $branch_id = $this->current_admin_branch_id();
        $datay = $this->queries->get_productAll($branch_id);
        $limit = $this->queries->get_stock_limitData();
        $my = $this->queries->get_mydata($user_id);
        $kwisha = $this->queries->get_bidhaa_kwisha($branch_id);
          // print_r($product);
          //   exit();
        $data = array();
        // Retrieve cart data from the session
        $cartItems = $this->cart->contents();
        $cart_product_ids = array_column($cartItems, 'id');
        $discount_rules = $this->queries->get_active_discount_rules($branch_id);
        $product_categories = $this->queries->get_product_category_map($cart_product_ids);
        // print_r($data);
        //      exit();
        
        // Load the cart view
        $this->load->view('admin/sell',['cartItems'=>$cartItems,'datay'=>$datay,'limit'=>$limit,'my'=>$my,'kwisha'=>$kwisha,'selected_branch_id'=>$branch_id,'discount_rules'=>$discount_rules,'product_categories'=>$product_categories]);
    }

    
    function removeItem($rowid){
        // Remove item from cart
        $remove = $this->cart->remove($rowid);
        
        // Redirect to the cart page
        redirect('admin_cart/');
    }

        //session destroy
        public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        if (!$this->session->userdata("user_id"))
        return redirect("home/index");
    }

    
}
