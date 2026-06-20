<?php
defined('BASEPATH') OR exit('No direct script access allowed');
@require_once './assets/library/php-excel-reader/excel_reader2.php';
@require_once './assets/library/SpreadsheetReader.php';

class Admin extends CI_Controller {
  private function allowed_product_categories(){
    return ['Medicines', 'Cosmetics', 'Skin Care', 'Medical Equipment'];
  }

  private function current_admin_branch_id(){
    $branch_id = $this->input->get('branch_id', TRUE);

    if ($branch_id !== null) {
      if ($branch_id === '') {
        $this->session->unset_userdata('admin_branch_id');
        return null;
      }

      $branch_id = (int) $branch_id;
      $this->session->set_userdata('admin_branch_id', $branch_id);
      return $branch_id;
    }

    $session_branch_id = $this->session->userdata('admin_branch_id');
    return $session_branch_id ? (int) $session_branch_id : null;
  }

  public function switch_branch(){
    $branch_id = $this->input->post('branch_id', TRUE);

    if ($branch_id === null) {
      $branch_id = $this->input->get('branch_id', TRUE);
    }

    if ($branch_id === null || $branch_id === '') {
      $this->session->unset_userdata('admin_branch_id');
    } else {
      $this->session->set_userdata('admin_branch_id', (int) $branch_id);
    }

    $redirect_url = $this->input->post('redirect_url', TRUE);
    if (!$redirect_url) {
      $redirect_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('admin/index');
    }

    if (strpos($redirect_url, base_url()) !== 0) {
      $redirect_url = base_url('admin/index');
    }

    return redirect($redirect_url);
  }

	public function index(){
		$this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
		$all_salles = $this->queries->get_sallesTodayData($selected_branch_id);
		$total_sell = $this->queries->get_today_salesData($selected_branch_id);
		$total_profit = $this->queries->get_today_profit($selected_branch_id);
    $today_retail_sales = $this->queries->get_today_salesretail_cashire($selected_branch_id);
    $today_wholesale_sales = $this->queries->get_today_salesWhole_cashire($selected_branch_id);
    $total_matumiz = $this->queries->All_totalMatumizi($selected_branch_id);
    $total_sell_data = $this->queries->get_All_salesData($selected_branch_id);
    $mishahara_data = $this->queries->get_sumPayrol();
    $my = $this->queries->get_mydata($user_id);
    $total_huduma = $this->queries->get_total_huduma();
    $years = $this->queries->get_month_sell();

    $year = $this->input->post('year');
    $datamonth = $this->queries->get_Yearly_month($year);
    $today_indirect_exp = $this->queries->get_today_indirectExpenses();


    //new code 
  $all_matumiz_all = $this->queries->All_totalMatumizi($selected_branch_id);
  $all_sell_all = $this->queries->get_All_salesData($selected_branch_id);
  $mishahara_data_all = $this->queries->get_sumPayrol();
  $huduma_all = $this->queries->get_total_service_todayData();
  $inderect_expenses_all = $this->queries->get_total_indirect_expenses();
  $total_profit_all = $this->queries->get_total_profit($selected_branch_id);
  
  // Get medicine movement data
  $fastmoving_medicines = $this->queries->get_fastmoving_medicines();
  $slowmoving_medicines = $this->queries->get_slowmoving_medicines();
  $medicine_movement_summary = $this->queries->get_medicine_movement_summary();
  $zero_balance_medicines = $this->queries->get_zero_balance_medicines();
  $empty_products_count = $this->queries->get_empty_products_count($selected_branch_id);

  // metric mode: 'skus' or 'units' (default skus)
  $metric_mode = $this->input->post('metric_mode');
  if ($metric_mode) {
    // persist choice in session so it survives navigation
    $this->session->set_userdata('metric_mode', $metric_mode);
  } else {
    $metric_mode = $this->session->userdata('metric_mode') ?: $this->input->get('metric_mode') ?: 'skus';
  }

  if ($metric_mode === 'units') {
    $purchased_today_count = $this->queries->get_purchased_products_today_units_count($selected_branch_id);
    $adjusted_today_count = $this->queries->get_adjusted_products_today_units_count($selected_branch_id);
  } else {
    $purchased_today_count = $this->queries->get_purchased_products_today_count($selected_branch_id);
    $adjusted_today_count = $this->queries->get_adjusted_products_today_count($selected_branch_id);
  }
    //$total_sell = $this->queries->get_Yearly_monthsELL($year);
		 // echo "<pre>";
		 // print_r($datamonth);
		 // echo "</pre>";
		 //     exit();
    $this->load->view('admin/index',['all_salles'=>$all_salles,'total_sell'=>$total_sell,'total_profit'=>$total_profit,'today_retail_sales'=>$today_retail_sales,'today_wholesale_sales'=>$today_wholesale_sales,'total_matumiz'=>$total_matumiz,'total_sell_data'=>$total_sell_data,'mishahara_data'=>$mishahara_data,'my'=>$my,'total_huduma'=>$total_huduma,'years'=>$years,'datamonth'=>$datamonth,'today_indirect_exp'=>$today_indirect_exp,'all_matumiz_all'=>$all_matumiz_all,'all_sell_all'=>$all_sell_all,'mishahara_data_all'=>$mishahara_data_all,'huduma_all'=>$huduma_all,'huduma_all'=>$huduma_all,'inderect_expenses_all'=>$inderect_expenses_all,'total_profit_all'=>$total_profit_all,'fastmoving_medicines'=>$fastmoving_medicines,'slowmoving_medicines'=>$slowmoving_medicines,'medicine_movement_summary'=>$medicine_movement_summary,'zero_balance_medicines'=>$zero_balance_medicines,'empty_products_count'=>$empty_products_count,'purchased_today_count'=>$purchased_today_count,'adjusted_today_count'=>$adjusted_today_count,'metric_mode'=>$metric_mode,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
	}



	public function users(){
		$this->load->model('queries');
    $this->queries->ensure_branch_table();
    $user_id = $this->session->userdata('user_id');
		 $admin = $this->queries->get_users();
     $my = $this->queries->get_mydata($user_id);
     $privillage_map = $this->queries->get_privillage_map();
     $branches = $this->queries->get_branches();
    $this->load->view('admin/users',['admin'=>$admin,'my'=>$my,'privillage_map'=>$privillage_map,'branches'=>$branches]);
	}

  public function branches(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $branches = $this->queries->get_branches();
    $shop_info = $this->queries->get_shop_infoData();

    $this->load->view('admin/branches', [
      'my' => $my,
      'branches' => $branches,
      'shop_info' => $shop_info,
    ]);
  }

  public function create_branch(){
    $this->form_validation->set_rules('branch_name', 'branch name', 'required|trim');
    $this->form_validation->set_rules('location', 'location', 'required|trim');
    $this->form_validation->set_rules('phone', 'phone', 'required|trim');
    $this->form_validation->set_rules('email', 'email', 'trim|valid_email');
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

    if (!$this->form_validation->run()) {
      return $this->branches();
    }

    $this->load->model('queries');
    $shop_info = $this->queries->get_shop_infoData();

    $data = [
      'information_id' => !empty($shop_info->id) ? $shop_info->id : null,
      'branch_name' => $this->input->post('branch_name', true),
      'location' => $this->input->post('location', true),
      'phone' => $this->input->post('phone', true),
      'email' => $this->input->post('email', true),
      'is_main' => 0,
      'status' => $this->input->post('status', true) ?: 'open',
    ];

    if ($this->queries->insert_branch($data)) {
      $this->session->set_flashdata('massage', 'Branch registered successfully');
    } else {
      $this->session->set_flashdata('error', 'Failed to register branch');
    }

    return redirect('admin/branches');
  }

  public function backfill_branch_data(){
    $this->load->model('queries');
    $main_branch = $this->queries->get_main_branch();

    if (!$main_branch) {
      $this->session->set_flashdata('error', 'Create a branch first before backfilling data.');
      return redirect('admin/branches');
    }

    $result = $this->queries->backfill_missing_branch_data($main_branch->branch_id);

    if (!$result) {
      $this->session->set_flashdata('error', 'Backfill failed.');
      return redirect('admin/branches');
    }

    $this->session->set_flashdata(
      'massage',
      'Backfill completed to '.$main_branch->branch_name.'. Updated '.$result['total'].' records.'
    );

    return redirect('admin/branches');
  }

  public function validate_seller_access($role){
    $system_access = (array) $this->input->post('system_access');

    if ($role === 'seller' && empty($system_access)) {
      $this->form_validation->set_message('validate_seller_access', 'Select at least one System Access option for seller.');
      return false;
    }

    return true;
  }

	public function create_admin(){
		$this->form_validation->set_rules('full_name','Name','required');
		$this->form_validation->set_rules('phone_number','Phone number','required');
    $this->form_validation->set_rules('role','privillage','required|callback_validate_seller_access');
    $this->form_validation->set_rules('password','password','required|min_length[4]');
    $this->form_validation->set_rules('confirm_password','confirm password','required|matches[password]');
		$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
		if ($this->form_validation->run() ) {
			$data = $this->input->post();
      $system_access = array_values(array_intersect((array) $this->input->post('system_access'), array('seller', 'product', 'store')));
      $data['full_name'] = $this->security->sanitize_filename($this->input->post('full_name'));
      $data['phone_number'] = $this->security->sanitize_filename($this->input->post('phone_number'));
      unset($data['confirm_password'], $data['system_access']);
      if (($data['role'] === 'seller' || $data['role'] === 'cashier') && empty($data['branch_id'])) {
        $this->session->set_flashdata('error', 'Select branch for seller or cashier.');
        return redirect('admin/users');
      }
      if ($data['role'] === 'admin') {
        $data['branch_id'] = null;
      }
			$data['password'] = sha1($this->input->post('password'));
      
    
			//  echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			//      exit();
			$this->load->model('queries');
      $user_id = $this->queries->insert_admin($data);
      if ($user_id) {
        if ($data['role'] === 'seller' && !empty($system_access)) {
          $this->queries->insert_user_privillages($user_id, $system_access);
        }
        $action ="Register customer";
        //$this->insert_register_customer_activity($data['full_name'],$action);
        $this->session->set_flashdata('massage','Users registered successfully');
			}else{
				$this->session->set_flashdata('error','Failed');
			}
			return redirect('admin/users');
		}
		$this->users();
	}


  //   public function insert_register_customer_activity($full_name,$activity){
  //    $activity_date = date("Y-m-d");
  //    $user_id = $this->session->userdata('user_id');
  // $this->db->query("INSERT INTO tbl_activity (`user_id`,`activity`,`date_activity`,`action`) VALUES ('$user_id','$full_name','$activity_date')");
  //     }



	//delete user

	public function delete_user($user_id){
		$this->load->model('queries');
		if($this->queries->remove_user($user_id));
		$this->session->set_flashdata('massage','User Deleted successfully');
		return redirect('admin/users');	
	}
	//product
	public function product(){
		$this->load->model('queries');
    $this->queries->ensure_branch_table();
    $user_id = $this->session->userdata('user_id');
		$product = $this->queries->get_product();
    $my = $this->queries->get_mydata($user_id);
    $privillage = $this->queries->get_userPrivillage($user_id);
    $branches = $this->queries->get_branches();
    $selected_branch_id = $this->current_admin_branch_id();
		  // echo "<pre>";
		  // print_r($product);
		  // echo "</pre>";
		  //      exit();
		$this->load->view('admin/product',['product'=>$product,'my'=>$my,'privillage'=>$privillage,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
	}

	public function create_product(){
    $selected_branch_id = $this->current_admin_branch_id();
		$this->form_validation->set_rules('name','Product name','required');
		$this->form_validation->set_rules('category','Category','required|in_list['.implode(',', $this->allowed_product_categories()).']');
		$this->form_validation->set_rules('unit','Product unit','required');
		$this->form_validation->set_rules('user_id','user','required');
		$this->form_validation->set_rules('branch_id','branch','required');
		$this->form_validation->set_rules('buy_price','buy price','required');
		$this->form_validation->set_rules('price','sell price','required');
    $this->form_validation->set_rules('ju_price','sell price');
		$this->form_validation->set_rules('quantity','quantity','required');
    $this->form_validation->set_rules('bland','Bland');
    $this->form_validation->set_rules('exp_date','Expire Date');
    $this->form_validation->set_rules('stock_limit','stock limit');
		$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
		if ($this->form_validation->run() ) {
			$product = $this->input->post();
      if ($selected_branch_id) {
        $product['branch_id'] = $selected_branch_id;
      }
      if ($this->db->field_exists('reason', 'product')) {
        if (!isset($product['reason']) || trim((string)$product['reason']) === '') {
          $product['reason'] = 'purchased';
        }
      } else {
        unset($product['reason']);
      }
      unset($product['min_selling_price']);

			//  echo "<pre>";
			// print_r($product);
			// echo "</pre>";
			//    exit();
			$balance = $product['quantity'];
			$out_balance = 0;
      $total_buy = $product['buy_price'] * $product['quantity'];
      $total_sell = $product['price'] * $product['quantity'];
      //$total_ju = 0;
      $total_ju = $product['ju_price'] * $product['quantity'];
      $name = $product['name'];
     //  print_r($total_buy);
     //  print_r($total_sell);
     //  print_r($total_ju);
     //        exit();
			  // print_r($out_balance);
			  //     exit();
			$this->load->model('queries');
      $check = $this->queries->check_productName($name);
      if ($check->name == TRUE){
      $this->session->set_flashdata('mas',$name.' Already Exist');
      }elseif($check->name == FALSE){
       // echo "jina Halipo";
      $product_id = $this->queries->insert_product($product);
			if ($product_id > 0) {
        $branch_id = isset($product['branch_id']) ? (int)$product['branch_id'] : null;
        $this->insert_store($product_id,$balance,$out_balance,$total_buy,$total_sell,$total_ju,$branch_id);
        $this->insert_product_main_store($product_id,$balance,$total_buy,$total_sell,$total_ju,$out_balance);
        $movement_user_id = isset($product['user_id']) ? (int)$product['user_id'] : (int)$this->session->userdata('user_id');
        $this->record_stock_movement($product_id, $balance, $movement_user_id, 'PURCHASED');

				$this->session->set_flashdata('massage','Product saved successfully');
			}else{
				$this->session->set_flashdata('error','failed');
			}
			return redirect('admin/product');
		}
    }
		$this->product();
	}



   public function transifor_product(){
        $this->form_validation->set_rules('product_id','product','required');
        $this->form_validation->set_rules('product_id','product','required');
        $this->form_validation->set_rules('balance','balance','required');
        $this->form_validation->set_rules('user_id','user','required');
        if ($this->form_validation->run()) {
           $data = $this->input->post();
           $product_id = $data['product_id'];
           $balance = $data['balance'];
           $user_id = $data['user_id'];
           $input_balance = $balance;
            
           $this->load->model('queries');
           $product = $this->queries->get_product_safe($product_id);
           $store_balance = $this->queries->get_product_inSTore($product_id);
           $pharmacy_balance = $store_balance->balance;
           $qnty_product = $pharmacy_balance + $input_balance;

           $main_stoo = $this->queries->get_mainTransforbalance($product_id);
           $main_balance = $main_stoo->quantity_product;
           $moved_quantity = $main_stoo->moved_qnty;

           $remove_balance = $main_balance - $input_balance;

           $update_remove = $moved_quantity + $input_balance;


           $product_id = $product->id;
           $price = $product->price;
           $buy_price = $product->buy_price;
           $ju_price = $product->ju_price;
           
           $out_balance = 0;
           $total_buy = $buy_price * $qnty_product;
           $total_sell = $price * $qnty_product;
           $total_ju = $ju_price * $qnty_product;
             if ($input_balance > $main_balance) {
             $this->session->set_flashdata("error",'The product is not enough');

            }elseif($this->update_pharmacy_stoo($product_id,$qnty_product,$total_buy,$total_sell,$total_ju,$out_balance)) {
              $this->update_main_stoo($product_id,$remove_balance,$update_remove);
              $this->insert_trans_recod($product_id,$input_balance,$user_id);
              $this->insert_stock_movement($product_id,$input_balance,$user_id);
            $this->session->set_flashdata('massage','Product Transfor successfully');
                }else{
                $this->session->set_flashdata('massage','Product Transfor successfully');
                }

                return redirect('admin/dispency_product');
        }
        $this->dispency_product();
      }

      //stock movement
      public function insert_stock_movement($product_id,$input_balance,$user_id){
         $this->record_stock_movement($product_id, $input_balance, $user_id, 'STOCK TRANSFOR');
      }

      public function record_stock_movement($product_id, $quantity, $user_id, $status, $movement_date = null){
        $movement_date = $movement_date ? $movement_date : date("Y-m-d");
        $this->db->query("INSERT INTO tbl_stock_movement (`product_id`, `product_qnty`,`user_id`,`date`,`mov_status`) VALUES ('".(int)$product_id."', '".(int)$quantity."','".(int)$user_id."','".$movement_date."','".$this->db->escape_str($status)."')");
      }
    

//insert transfor recod
  public function insert_trans_recod($product_id,$input_balance,$user_id){
    $date = date("Y-m-d");
  $this->db->query("INSERT INTO tbl_trans_recod (`product_id`, `quantity`,`user_id`,`date`) VALUES ('$product_id', '$input_balance','$user_id','$date')");
      }

//upadet main stooo
  public function update_main_stoo($product_id,$remove_balance,$update_remove){
  $sqldata="UPDATE `tbl_main_store` SET `quantity_product`= '$remove_balance',`moved_qnty`= '$update_remove' WHERE `product_id`= '$product_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

//update pharmacy stoo
  public function update_pharmacy_stoo($product_id,$qnty_product,$total_buy,$total_sell,$total_ju,$out_balance){
  $sqldata="UPDATE `tbl_store` SET `balance`= '$qnty_product',`total_buy`= '$total_buy',`total_sell`='$total_sell',`total_ju`='$total_ju',`out_balance`='$out_balance' WHERE `product_id`= '$product_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}


	  public function insert_store($product_id,$balance,$out_balance,$total_buy,$total_sell,$total_ju,$branch_id = null){
  if ($this->db->field_exists('branch_id', 'tbl_store')) {
    $branch_id = (int) $branch_id;
    $this->db->query("INSERT INTO tbl_store (`product_id`, `branch_id`, `balance`,`out_balance`,`total_buy`,`total_sell`,`total_ju`) VALUES ('$product_id', '$branch_id', '$balance','$out_balance','$total_buy','$total_sell','$total_ju')");
    return;
  }
  $this->db->query("INSERT INTO tbl_store (`product_id`, `balance`,`out_balance`,`total_buy`,`total_sell`,`total_ju`) VALUES ('$product_id', '$balance','$out_balance','$total_buy','$total_sell','$total_ju')");
      }

  public function insert_product_main_store($product_id,$balance,$total_buy,$total_sell,$total_ju,$out_balance){
  $this->db->query("INSERT INTO tbl_main_store (`product_id`,`quantity_product`,`total_buy_price`,`total_sell_price`,`total_sellju_price`,`moved_qnty`) VALUES ('$product_id','$balance','$total_buy','$total_sell','$total_ju','$out_balance')");
  }



  public function edit_product($id){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $productE = $this->queries->get_edit_peoduct($id);
      $privillage = $this->queries->get_userPrivillage($user_id);
        // print_r($productE);
        //    exit();
      $this->load->view('admin/edit_product',['productE'=>$productE,'my'=>$my,'privillage'=>$privillage]);
    }

    public function modify_product($id){
    $this->form_validation->set_rules('name','Product name','required');
    $this->form_validation->set_rules('category','Category','required|in_list['.implode(',', $this->allowed_product_categories()).']');
    $this->form_validation->set_rules('unit','Product unit','required');
    $this->form_validation->set_rules('buy_price','buy price','required');
    $this->form_validation->set_rules('price','sell price','required');
    $this->form_validation->set_rules('ju_price','sell price');
    $this->form_validation->set_rules('bland','bland');
    $this->form_validation->set_rules('exp_date','Expire date');
    $this->form_validation->set_rules('stock_limit','stock limit');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run() ) {
         $data = $this->input->post();
         unset($data['min_selling_price']);
         // echo "<pre>";
         // print_r($data);
         //  echo "</pre>";
         //     exit();
         $this->load->model('queries');
         $datas = $this->queries->get_edit_peoduct($id);
         $total_buy = $data['buy_price'] * $datas->balance;
         $sell_total = $data['price'] * $datas->balance;
         $total_ju = $data['ju_price'] * $datas->balance;
         $id = $datas->product_id;
         $product_id = $datas->id;

         $buyp = $data['buy_price'];
         $sellp = $data['price'];
         $sellju = $data['ju_price'];
         $name = $data['name'];
      
         $main_stoo = $this->queries->get_mainTransforbalance($product_id);
         $quantity_product = $main_stoo->quantity_product;
         $new_update_buy = $quantity_product * $buyp;
         $new_update_sell = $quantity_product * $sellp;
         $new_update_sellju = $quantity_product * $sellju;
       
          if ($this->queries->update_product($data,$id)) {
             $this->update_quantity_store($id,$total_buy,$sell_total,$total_ju);
             //$this->update_price_main_stoo($product_id,$new_update_buy,$new_update_sell,$new_update_sellju);
                $this->session->set_flashdata('massage',$name.' Updated successfully');
          }else{
           $this->session->set_flashdata('error','Failed');  
          }
          return redirect('admin/edit_product/'.$id);
    }
    $this->edit_product();
    }

     public function add_product_store(){
      $this->form_validation->set_rules('product_id','Product','required');
      $this->form_validation->set_rules('balance','quantity','required');
      $this->form_validation->set_rules('reason','reason','required');
      $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $this->load->model('queries');
        $selected_branch_id = $this->current_admin_branch_id();
        $product_id = $data['product_id'];
        $balance = $data['balance'];
        $reason = isset($data['reason']) ? strtolower(trim((string)$data['reason'])) : '';

        if (!in_array($reason, array('purchased', 'adjusted'), true)) {
          $this->session->set_flashdata('error','Please select a valid reason');
          return redirect("admin/product_add_Store");
        }

        $product = $this->queries->get_product_safe($product_id);
        if ($selected_branch_id && (!$product || (int)$product->branch_id !== (int)$selected_branch_id)) {
          $this->session->set_flashdata('error','This product is not available in the selected branch');
          return redirect("admin/product_add_Store");
        }
        $buy_price = $product->buy_price;
        $sell_price = $product->price;
        $product_name = $product->name;
        $ju_sellprice = $product->ju_price;
        $main_stoo = $this->queries->get_mainTransforbalance($product_id, $selected_branch_id);
        if (!$main_stoo) {
          $this->session->set_flashdata('error','Product store record was not found for the selected branch');
          return redirect("admin/product_add_Store");
        }
        $remain_balance = $main_stoo->balance;

        $moved_product = 0;
        $new_balance = $remain_balance + $balance;
        //     echo "<pre>";
        // print_r($buy_price);
        //            exit();
        $total_buy = $new_balance * $buy_price;
        $total_sell = $new_balance * $sell_price;
        $total_jusell = $new_balance * $ju_sellprice; 

       
      if ($this->update_main_stooProduct($product_id,$new_balance,$total_buy,$total_sell,$moved_product,$total_jusell,$selected_branch_id)) {
        $movement_status = ($reason === 'adjusted') ? 'ADJUSTED IN' : 'PURCHASED';
        $movement_user_id = (int)$this->session->userdata('user_id');
        $this->record_stock_movement($product_id, $balance, $movement_user_id, $movement_status);
        if ($this->db->field_exists('reason', 'tbl_store')) {
          $this->db->where('product_id', $product_id);
          $this->db->update('tbl_store', array('reason' => $reason));
        }
        if ($this->db->field_exists('reason', 'product')) {
          $this->db->where('id', $product_id);
          $this->db->update('product', array('reason' => $reason));
        }
        $this->session->set_flashdata('massage',$product_name.' Added successfully');
          }else{
          $this->session->set_flashdata('error','Failed');
          }
       return redirect("admin/product_add_Store");
        }
      $this->product_add_Store();
     }


     public function remove_product_store(){
      $this->form_validation->set_rules('product_id','Product','required');
      $this->form_validation->set_rules('balance','quantity','required');
      $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $this->load->model('queries');
        $product_id = $data['product_id'];
        $balance = $data['balance'];

        $product = $this->queries->get_product_safe($product_id);
        $buy_price = $product->buy_price;
        $sell_price = $product->price;
        $product_name = $product->name;
        $ju_sellprice = $product->ju_price;
        $main_stoo = $this->queries->get_mainTransforbalance($product_id);
        $remain_balance = $main_stoo->balance;
        $moved_product = 0;
        $new_balance = $remain_balance - $balance;
          //   echo "<pre>";
          // print_r($new_balance);
          //      exit();
        $total_buy = $new_balance * $buy_price;
        $total_sell = $new_balance * $sell_price;
        $total_jusell = $new_balance * $ju_sellprice; 
       
      if ($this->update_main_stooProduct($product_id,$new_balance,$total_buy,$total_sell,$moved_product,$total_jusell)) {
        $movement_user_id = (int)$this->session->userdata('user_id');
        $this->record_stock_movement($product_id, $balance, $movement_user_id, 'ADJUSTED OUT');
        $this->session->set_flashdata('massage',$product_name.' Adjust successfully');
          }else{
          $this->session->set_flashdata('error','Failed');
          }
       return redirect("admin/adjust_product_stoo");
        }
      $this->adjust_product_stoo();
     }


public function update_main_stooProduct($product_id,$new_balance,$total_buy,$total_sell,$moved_product,$total_jusell,$branch_id = null){
  $branch_sql = $branch_id !== null ? " AND `branch_id` = '".(int)$branch_id."'" : "";
  $sqldata="UPDATE `tbl_store` SET `balance`= '$new_balance',`total_buy`='$total_buy',`total_sell`= '$total_sell',`out_balance`='$moved_product',`total_ju`='$total_jusell' WHERE `product_id`= '$product_id' $branch_sql";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

//update price stoo
public function update_price_main_stoo($product_id,$new_update_buy,$new_update_sell,$new_update_sellju){
$sqldata="UPDATE `tbl_main_store` SET `total_buy_price`= '$new_update_buy',`total_sell_price`= '$new_update_sell',`total_sellju_price`='$new_update_sellju' WHERE `product_id`= '$product_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
 }


//update quantity stoo
  public function update_quantity_store($id,$total_buy,$sell_total,$total_ju){
  $sqldata="UPDATE `tbl_store` SET `total_buy`= '$total_buy',`total_sell`= '$sell_total',`total_ju`='$total_ju' WHERE `product_id`= '$id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}



  public function produc_available_store(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $store_product = $this->queries->get_store_product_available();
    $buy_price = $this->queries->get_total_price_store();
    $sell_price = $this->queries->get_sell_price_store();
    $product = $this->queries->get_store_product_available();
    $whole_sale = $this->queries->get_total_sumRetail();
    $total_pricewhole = $this->queries->get_sumRetail();
    $privillage = $this->queries->get_userPrivillage($user_id);
      //  echo "<pre>";
      // print_r($total_pricewhole);
      //       exit();
    $this->load->view('admin/store_available_product',['my'=>$my,'store_product'=>$store_product,'buy_price'=>$buy_price,'sell_price'=>$sell_price,'product'=>$product,'whole_sale'=>$whole_sale,'total_pricewhole'=>$total_pricewhole,'privillage'=>$privillage]);
  }


  public function print_product_available_store(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $shop = $this->queries->get_shop_infoData();
    $store_product = $this->queries->get_store_product_available();
    $buy_price = $this->queries->get_total_price_store();
    $sell_price = $this->queries->get_sell_price_store();
    $whole_sale = $this->queries->get_total_sumRetail();
    $total_pricewhole = $this->queries->get_sumRetail();
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4-L','orientation' => 'L']);
    $html = $this->load->view('admin/store_product_report',['my'=>$my,'shop'=>$shop,'store_product'=>$store_product,'buy_price'=>$buy_price,'sell_price'=>$sell_price,'whole_sale'=>$whole_sale,'total_pricewhole'=>$total_pricewhole],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }


      public function all_product(){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
        $branch_id = $this->current_admin_branch_id();
        $branches = $this->queries->get_branches();
      	$product = $this->queries->get_productAll($branch_id);
        $privillage = $this->queries->get_userPrivillage($user_id);
      	   // print_r($product);
      	   //     exit();
      	$this->load->view('admin/all_product',['product'=>$product,'my'=>$my,'privillage'=>$privillage,'branches'=>$branches,'selected_branch_id'=>$branch_id]);
      }

      public function all_productStore(){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
        $branch_id = $this->current_admin_branch_id();
        $branches = $this->queries->get_branches();
      	$store = $this->queries->get_productAllStore($branch_id);
      	$data_limit = $this->queries->get_stock_limitData();
        $privillage = $this->queries->get_userPrivillage($user_id);
      	  //  echo "<pre>";
      	  // print_r($data_limit);
      	  //  echo "</pre>";
      	  //    exit();
      	$this->load->view('admin/store_product',['store'=>$store,'data_limit'=>$data_limit,'my'=>$my,'privillage'=>$privillage,'branches'=>$branches,'selected_branch_id'=>$branch_id]);
      }

      public function dispency_product(){
        $this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
        $store_product = $this->queries->get_store_product_available();
        $stock = $this->queries->get_product_pharmacy();
        $trans = $this->queries->get_transfor_recod();
        $privillage = $this->queries->get_userPrivillage($user_id);
           // print_r($trans);
           //        exit();
        $this->load->view('admin/transifor',['my'=>$my,'store_product'=>$store_product,'stock'=>$stock,'trans'=>$trans,'privillage'=>$privillage]);
      }


    public function print_previous_transfor(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $shop = $this->queries->get_shop_infoData();
    $trans = $this->queries->get_transfor_recod();

    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/print_transfor',['shop'=>$shop,'trans'=>$trans],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
      }


      public function print_expired_product(){
      $this->load->model('queries');
      $expired = $this->queries->get_expired_product();
      $shop = $this->queries->get_shop_infoData();
         //  echo "<pre>";
         // print_r($expired);
         //       exit();
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/expired_product',['shop'=>$shop,'expired'=>$expired],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
      }


      public function previous_transfor(){
        $this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $data = $this->queries->get_previous_reportData($from,$to);
        $privillage = $this->queries->get_userPrivillage($user_id);
        // print_r($data);
        //      exit();
      $this->load->view('admin/previous_transfor',['my'=>$my,'data'=>$data,'from'=>$from,'to'=>$to,'privillage'=>$privillage]);
      }

    public function print_previous_trans($from,$to){
      $this->load->model('queries');
    $data = $this->queries->get_previous_reportData($from,$to);
    $shop = $this->queries->get_shop_infoData();

    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/previuos_transfor_report',['shop'=>$shop,'data'=>$data,'from'=>$from,'to'=>$to],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
      }






function fetch_ward_data()
{
$this->load->model('queries');
if($this->input->post('product_id'))
{
echo $this->queries->fetch_eneos($this->input->post('product_id'));
}
}

// function fetch_data_vipimioData()
// {
// $this->load->model('queries');
// if($this->input->post('region_id'))
// {
// echo $this->queries->fetch_vipmios($this->input->post('region_id'));
// }
// }


      public function inventory(){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
        $selected_branch_id = $this->current_admin_branch_id();
        $branches = $this->queries->get_branches();
      	$inve = $this->queries->get_all_inventory($selected_branch_id);
        $sum_total_buy = $this->queries->get_sum_buyPrice($selected_branch_id);
        $sum_total_retail = $this->queries->get_total_retail_salePrice($selected_branch_id);
        $total_wholesale = $this->queries->get_suwhole_sale($selected_branch_id);
        $total_sell_profit = $this->queries->get_all_sells($selected_branch_id);
      	  // echo "<pre>";
      	  // print_r($total_sell_profit);
      	  // echo "</pre>";
      	  //    exit();
      	$this->load->view('admin/inventory',['inve'=>$inve,'my'=>$my,'sum_total_buy'=>$sum_total_buy,'sum_total_retail'=>$sum_total_retail,'total_wholesale'=>$total_wholesale,'total_sell_profit'=>$total_sell_profit,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
      }

      public function stock_limit(){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
      	$stock = $this->queries->get_stock_limit();
      	$stock_data = $this->queries->get_stock_limitData();
      	 // print_r($stock_data);
      	 //     exit();
      	$this->load->view('admin/stock_limit',['stock'=>$stock,'stock_data'=>$stock_data,'my'=>$my]);
      }

      public function create_stock_limit(){
      	$this->form_validation->set_rules('stock','stock limit','required');
      	$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
      	if ($this->form_validation->run() ) {
      		$data = $this->input->post();
      		// print_r($data);
      		//      exit();
      		$this->load->model('queries');
      		if ($this->queries->insert_stockLimit($data)) {
      			$this->session->set_flashdata('massage','Stock limit saved successfully');
      		}else{
      		$this->session->set_flashdata('error','Failed');	
      		}
      		return redirect('admin/stock_limit');
      	}
      	$this->stock_limit();
      }

      public function edit_stock_limit($id){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
      	$data = $this->queries->get_edit_stock($id);
      	 // print_r($data);
      	 //    exit();
      	$this->load->view('admin/edit_stock_limit',['data'=>$data,'my'=>$my]);
      }

      public function modify_stock_limit($id){
      	$this->form_validation->set_rules('stock','stock limit','required');
      	$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
      	if ($this->form_validation->run() ) {
      		$data = $this->input->post();
      		// print_r($data);
      		//      exit();
      		$this->load->model('queries');
      		if ($this->queries->update_stockLimit($data,$id)) {
      			$this->session->set_flashdata('massage','Stock limit saved successfully');
      		}else{
      		$this->session->set_flashdata('error','Failed');	
      		}
      		return redirect('admin/stock_limit');
      	}
      	$this->stock_limit();
      }

      public function get_all_cashFlow(){
      	$this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $my = $this->queries->get_mydata($user_id);
      	$data_cash = $this->queries->get_all_cashflow();
      	  //    echo "<pre>";
      	  // print_r($data_cash);
      	  //    echo "</pre>";
      	  //   exit();
      	$this->load->view('admin/cash_flow',['data_cash'=>$data_cash,'my'=>$my]);
      }

      public function create_useToday(){
  $this->form_validation->set_rules('price','price','required');
  $this->form_validation->set_rules('description','description','required');
  $this->form_validation->set_rules('user_id','user_id','required');
  //$this->form_validation->set_rules('day','day','required');
  $this->form_validation->set_rules('<div class="text-danger">','</div>');
  if ($this->form_validation->run() ) {
     $data = $this->input->post();
     $data['day'] = date("Y-m-d");
     $this->load->model('queries');
     // print_r($data);
     //    exit();
     if ($this->queries->insert_useToday($data)) {
       $this->session->set_flashdata('massage','Day use saved successfully');
     }else{
       $this->session->set_flashdata('error','Failed');
     }
     return redirect('admin/get_all_cashFlow');
  }
  $this->get_all_cashFlow();
}

public function today_cashflow(){
	$this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
	$data_cash = $this->queries->get_all_cashflow();
	$total_expences = $this->queries->get_totalEpences();
	$data_cashin = $this->queries->get_today_salesData();
  $huduma = $this->queries->get_total_service_today(); 
  $today_profit = $this->queries->get_today_profit();
  $today_indirect_exp = $this->queries->get_today_indirectExpenses();

  //$today_profits = $this->queries->get_total_profit_today();
	  // print_r($today_profit);
	  //      exit();
	$this->load->view('admin/today_cashflow',['data_cash'=>$data_cash,'total_expences'=>$total_expences,'data_cashin'=>$data_cashin,'my'=>$my,'huduma'=>$huduma,'today_profit'=>$today_profit,'today_indirect_exp'=>$today_indirect_exp]);
}


public function today_cashreport(){
      $this->load->model('queries');
      $data_cash = $this->queries->get_all_cashflow();
      $total_expences = $this->queries->get_totalEpences();
      $data_cashin = $this->queries->get_today_salesData();
      $shop = $this->queries->get_shop_infoData();

      $today_profit = $this->queries->get_today_profit();
      $today_indirect_exp = $this->queries->get_today_indirectExpenses();

         // print_r($shop);
         //    exit();
      $mpdf = new \Mpdf\Mpdf();
       $html = $this->load->view('admin/cashflow_report',['data_cash'=>$data_cash,'total_expences'=>$total_expences,'data_cashin'=>$data_cashin,'shop'=>$shop,'today_profit'=>$today_profit,'today_indirect_exp'=>$today_indirect_exp],true);
       $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();

  }

public function genel_cashflow(){
	$this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
	$data = $this->queries->get_all_cashflowData();
  $all_matumiz = $this->queries->All_totalMatumizi();
  $all_sell = $this->queries->get_All_salesData();
  $mishahara_data = $this->queries->get_sumPayrol();
  $huduma = $this->queries->get_total_service_todayData();
  $inderect_expenses = $this->queries->get_total_indirect_expenses();
  $total_profit = $this->queries->get_total_profit();

	 // echo "<pre>";
	 // print_r($total_profit);
	 // echo "</pre>";
	 //    exit();
   $this->load->view('admin/general_cashflow',['data'=>$data,'all_matumiz'=>$all_matumiz,'all_sell'=>$all_sell,'mishahara_data'=>$mishahara_data,'my'=>$my,'huduma'=>$huduma,'inderect_expenses'=>$inderect_expenses,'total_profit'=>$total_profit]);
    }

     public function general_cashflow_report(){
  $this->load->model('queries');
  $data = $this->queries->get_all_cashflowData();
  $all_matumiz = $this->queries->All_totalMatumizi();
  $all_sell = $this->queries->get_All_salesData();
  $mishahara_data = $this->queries->get_sumPayrol();
  $shop = $this->queries->get_shop_infoData();
  $huduma = $this->queries->get_total_service_todayData();
  $inderect_expenses = $this->queries->get_total_indirect_expenses();
  $total_profit = $this->queries->get_total_profit();



     $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/general_cashflow_report',['data'=>$data,'all_matumiz'=>$all_matumiz,'all_sell'=>$all_sell,'mishahara_data'=>$mishahara_data,'shop'=>$shop,'huduma'=>$huduma,'inderect_expenses'=>$inderect_expenses,'total_profit'=>$total_profit],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }

    public function edit_user($user_id){
      $this->load->model('queries');
      //$user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $admin = $this->queries->get_usersEdit($user_id);
      $branches = $this->queries->get_branches();
      $user_privillages = $this->queries->get_userPrivillage($user_id);
      $user_access = array();
      foreach ($user_privillages as $privillage) {
        $user_access[] = $privillage->privillage;
      }
        // print_r($admin);
        //     exit();
      $this->load->view('admin/edit_users',['admin'=>$admin,'my'=>$my,'branches'=>$branches,'user_access'=>$user_access]);
    }

    public function modify_admin($user_id){
      $this->form_validation->set_rules('full_name','Name','required');
    $this->form_validation->set_rules('phone_number','Phone number','required');
    $this->form_validation->set_rules('role','privillage','required|callback_validate_seller_access');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run() ) {
      $data = $this->input->post();
      $system_access = array_values(array_intersect((array) $this->input->post('system_access'), array('seller', 'product', 'store')));
      unset($data['system_access']);
      if (($data['role'] === 'seller' || $data['role'] === 'cashier') && empty($data['branch_id'])) {
        $this->session->set_flashdata('error','Select branch for seller or cashier.');
        return redirect('admin/edit_user/'.$user_id);
      }
      if ($data['role'] === 'admin') {
        $data['branch_id'] = null;
      }
      //  echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      //      exit();
      $this->load->model('queries');
      if ($this->queries->update_admin($data,$user_id)) {
        if ($data['role'] === 'seller') {
          $this->queries->replace_user_privillages($user_id, $system_access);
        } else {
          $this->queries->replace_user_privillages($user_id, array());
        }
        $this->session->set_flashdata('massage','Users Updated successfully');
      }else{
        $this->session->set_flashdata('error','Failed');
      }
      return redirect('admin/edit_user/'.$user_id);
    }
    $this->edit_user($user_id);
    }


    public function delete_product($id){
      $this->load->model('queries');
      if($this->queries->remove_product($id));
      $this->session->set_flashdata('massage','Product Deleted successfully');
      return redirect('admin/product');
    }

    public function addProduct($store_id){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $data = $this->queries->get_store_product($store_id);
        // print_r($data);
        //    exit();
      $this->load->view('admin/add_product',['data'=>$data,'my'=>$my]);
    }


         public function modify_quantity($store_id){
          $this->form_validation->set_rules('balance','quantity','required');
          $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
          if ($this->form_validation->run() ) {
              $data = $this->input->post();
              $this->load->model('queries');
                $quanty = $data['balance'];
                $out_balance = 0;


                 // print_r($quanty);
                 // print_r($out_balance);
                 //      exit();
        if ($this->update_store($store_id,$quanty,$out_balance)) {
            $data = $this->get_balance($store_id);
            $total_buy = $data->buy_price * $data->balance;
            $total_sell = $data->price * $data->balance;
            $total_ju = $data->ju_price * $data->balance;
            $store_id = $data->store_id;
              //   echo "<pre>";
              // print_r($store_id);
              //   echo "</pre>";
              //    exit();
            $this->update_all_price($store_id,$total_buy,$total_sell,$total_ju);

                   $this->session->set_flashdata('massage','successfully');
               }else{
                 $this->session->set_flashdata('error','Failed');
               }

               return redirect('admin/addProduct/'.$store_id);
          }
          $this->addProduct();
        }


  public function update_store($store_id,$quanty,$out_balance){
  $sqldata="UPDATE `tbl_store` SET `balance`= `balance`+$quanty,`out_balance`= '$out_balance' WHERE `store_id`= '$store_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

//get balance
public function get_balance($store_id){
 $sql_data = "SELECT * FROM tbl_store JOIN product ON product.id = tbl_store.product_id WHERE store_id = '$store_id'"; 
  $query = $this->db->query($sql_data);
       // print_r($query->result());
       //    exit();
   return $query->row();
    }

    //update_balance
  public function update_all_price($store_id,$total_buy,$total_sell,$total_ju){
  $sqldata="UPDATE `tbl_store` SET `total_buy`= '$total_buy',`total_sell`= '$total_sell',`total_ju`='$total_ju' WHERE `store_id`= '$store_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

public function sales_today(){
  $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $all_salles = $this->queries->get_sallesTodayData($selected_branch_id);
    $total_sell = $this->queries->get_today_salesData($selected_branch_id);
    $total_profit = $this->queries->get_today_profit($selected_branch_id);
    
    $data_employee = $this->queries->get_sallesTodayData_seller($selected_branch_id);
      //        echo "<pre>";
      // print_r($data_employee);
      //           exit();

  $this->load->view('admin/today_sales',['all_salles'=>$all_salles,'total_sell'=>$total_sell,'total_profit'=>$total_profit,'my'=>$my,'data_employee'=>$data_employee,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
}

public function general_sells_product(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $all_seller = $this->queries->get_all_sellers();
    $selected_user_id = $this->input->get('user_id', TRUE);
    if ($selected_user_id === null) {
      $selected_user_id = $this->input->post('user_id', TRUE);
    }
    $from = $this->input->get('from', TRUE);
    if ($from === null) {
      $from = $this->input->post('from', TRUE);
    }
    $to = $this->input->get('to', TRUE);
    if ($to === null) {
      $to = $this->input->post('to', TRUE);
    }

    $today = date('Y-m-d');
    $from = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$from) ? $from : $today;
    $to = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$to) ? $to : $today;
    if ($from > $to) {
      $temp = $from;
      $from = $to;
      $to = $temp;
    }

    $is_filtered = !empty($selected_user_id);
    if ($is_filtered) {
      $all_salles = $this->queries->search_mauzo_seller_data((int)$selected_user_id, $from, $to);
      $total_sell = $this->queries->search_profit_seller((int)$selected_user_id, $from, $to);
      $total_profit = $total_sell;
      $data_employee = $this->queries->search_mauzo_seller($from, $to);
    } else {
      $all_salles = $this->queries->get_sallesTodayData();
      $total_sell = $this->queries->get_today_salesData();
      $total_profit = $this->queries->get_today_profit();
      $data_employee = $this->queries->get_sallesTodayData_seller();
    }
     // print_r($all_seller);
     //       exit();
    $this->load->view('admin/seller_sells',['my'=>$my,'all_salles'=>$all_salles,'total_sell'=>$total_sell,'total_profit'=>$total_profit,'data_employee'=>$data_employee,'all_seller'=>$all_seller,'selected_user_id'=>$selected_user_id,'from'=>$from,'to'=>$to,'is_filtered'=>$is_filtered]);
  }

 


  public function edit_cashflow($id){
    $this->load->model('queries');
   $user_id = $this->session->userdata('user_id');
   $my = $this->queries->get_mydata($user_id);
   $cash = $this->queries->get_editCashflow($id);
      // print_r($cash);
      //     exit();
  $this->load->view('admin/edit_cashflow',['my'=>$my,'cash'=>$cash]);
  }


  public function modify_cashflow($id){
    $this->form_validation->set_rules('price','price','required');
    $this->form_validation->set_rules('description','description','required');
    $this->form_validation->set_rules('<div class="text-danger">','</div>');
    if ($this->form_validation->run() ) {
      $data = $this->input->post();
      // print_r($data);
      //      exit();
      $this->load->model('queries');
      if ($this->queries->update_cashflow($id,$data)) {
        $this->session->set_flashdata('massage','Data updated successfully');
      }else{
        $this->session->set_flashdata('error','Failed');
      }
      return redirect('admin/edit_cashflow/'.$id);
    }
    $this->edit_cashflow();
  }

  public function curent_sells(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $branch_input = $this->input->post('branch_id', TRUE);
    if ($branch_input === null) {
      $branch_input = $this->input->get('branch_id', TRUE);
    }
    if ($branch_input !== null) {
      if ($branch_input === '') {
        $this->session->unset_userdata('admin_branch_id');
        $selected_branch_id = null;
      } else {
        $selected_branch_id = (int)$branch_input;
        $this->session->set_userdata('admin_branch_id', $selected_branch_id);
      }
    } else {
      $selected_branch_id = $this->current_admin_branch_id();
    }
    $branches = $this->queries->get_branches();
    $today = date('Y-m-d');
    $from = $this->input->post('from', TRUE) ?: $this->input->get('from', TRUE);
    $to = $this->input->post('to', TRUE) ?: $this->input->get('to', TRUE);
    $from = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$from) ? $from : $today;
    $to = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$to) ? $to : $today;
    if ($from > $to) {
      $temp = $from;
      $from = $to;
      $to = $temp;
    }
    $data = $this->queries->search_mauzo($from,$to,$selected_branch_id);
    $total_mauzo_pita = $this->queries->search_mauzoPita($from,$to,$selected_branch_id);
    $total_profit = $this->queries->search_profit($from,$to,$selected_branch_id);
    $seller_data = $this->queries->search_mauzo_seller($from,$to,$selected_branch_id);
      //      echo "<pre>";
      // print_r($seller_data);
      //    exit();
    $this->load->view('admin/mauzo_yaliopita',['data'=>$data,'total_mauzo_pita'=>$total_mauzo_pita,'total_profit'=>$total_profit,'my'=>$my,'from'=>$from,'to'=>$to,'seller_data'=>$seller_data,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
     //$this->last_salesReport($from,$to);
  }

   public function filter_general_sellers(){
    $from = $this->input->post('from');
    $to = $this->input->post('to');
    $user_id = $this->input->post('user_id');
    if (!$from) {
      $from = $this->input->get('from');
    }
    if (!$to) {
      $to = $this->input->get('to');
    }
    if (!$user_id) {
      $user_id = $this->input->get('user_id');
    }
    return redirect('admin/general_sells_product?user_id='.(int)$user_id.'&from='.urlencode($from).'&to='.urlencode($to));
  }


  public function today_saled_report($user_id,$from,$to){
  $this->load->model('queries');
  $data = $this->queries->get_sallesToday($user_id);
  $data = $this->queries->search_mauzo_seller_data($user_id,$from,$to);
  $total_sell = $this->queries->search_profit_seller($user_id,$from,$to);
  $shop = $this->queries->get_shop_infoData();
  $user_data = $this->queries->get_user_data($user_id);

   // echo "<pre>";
   // print_r($data);
   // echo "</pre>";
   //     exit();
   $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/today_sales_report',['data'=>$data,'total_sell'=>$total_sell,'shop'=>$shop,'user_data'=>$user_data,'from'=>$from,'to'=>$to],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();

}





    public function last_salesReport($from,$to){
   $this->load->model('queries');
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $from = date('Y-m-d', strtotime($from));
    $to = date('Y-m-d', strtotime($to));
    if ($from > $to) {
      $temp = $from;
      $from = $to;
      $to = $temp;
    }
    $all_salles = $this->queries->search_mauzo($from,$to,$selected_branch_id);
    $total_mauzo_pita = $this->queries->search_mauzoPita($from,$to,$selected_branch_id);
    $total_profits = $this->queries->search_profit($from,$to,$selected_branch_id);
    $shop = $this->queries->get_shop_infoData();

    $seller_data = $this->queries->search_mauzo_seller($from,$to,$selected_branch_id);
     // echo "<pre>";
     //  print_r($all_salles);
     // echo "</pre>";
     //     exit();
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/mauzo_yaliopita_report',['all_salles'=>$all_salles,'total_mauzo_pita'=>$total_mauzo_pita,'total_profits'=>$total_profits,'shop'=>$shop,'from'=>$from,'to'=>$to,'seller_data'=>$seller_data,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }


  public function print_data(){
  $this->load->model('queries');
  $branch_id = $this->current_admin_branch_id();
  $data = $this->queries->get_all_inventory($branch_id);
  $balance = $this->queries->get_all_SUMbalanceinventory($branch_id);
  $total_buyprice = $this->queries->get_all_SUMbuypriceinventory($branch_id);
  $total_buyprice_data = $this->queries->get_all_Totalbuyprice($branch_id);
  $total_sell_price = $this->queries->get_all_selling_price($branch_id);
  $totalAll_sellprice = $this->queries-> get_all_Totalselling_price($branch_id);
  $shop = $this->queries->get_shop_infoData();
  $branches = $this->queries->get_branches();
  $bei_jumla = $this->queries->get_sum_jumlaPrice($branch_id);
  $total_jumla = $this->queries->get_total_beiju($branch_id);
             // print_r($total_jumla);
             //    exit();
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('admin/print_report',['data'=>$data,'balance'=>$balance,'total_buyprice'=>$total_buyprice,'total_buyprice_data'=>$total_buyprice_data,'total_sell_price'=>$total_sell_price,'totalAll_sellprice'=> $totalAll_sellprice,'shop'=>$shop,'branches'=>$branches,'selected_branch_id'=>$branch_id,'bei_jumla'=>$bei_jumla,'total_jumla'=>$total_jumla],true);
        $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        if ($this->input->get('download', TRUE)) {
          $filename = $branch_id ? 'all-products-branch-'.$branch_id.'.pdf' : 'all-products.pdf';
          return $mpdf->Output($filename, 'D');
        }
        $mpdf->Output();
  }


  public function print_sellingPrice(){
    $this->load->model('queries');
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $selling_priceData = $this->queries->get_sellinprice($selected_branch_id);
    $shop = $this->queries->get_shop_infoData();
      //   echo "<pre>";
      // print_r($selling_priceData);
      // echo "</pre>";
      //     exit();
      $mpdf = new \Mpdf\Mpdf();
       $html = $this->load->view('admin/selling_price',['selling_priceData'=>$selling_priceData,'shop'=>$shop,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
       $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }

  public function empty_product(){
      $this->load->model('queries');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $empty_prdData = $this->queries->get_emptyProduct($selected_branch_id);
      $shop = $this->queries->get_shop_infoData();

         // print_r($empty_prdData);
         //       exit();

      $mpdf = new \Mpdf\Mpdf();
       $html = $this->load->view('admin/empty_product',['empty_prdData'=>$empty_prdData,'shop'=>$shop,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
       $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }


  

  public function today_salles_report(){
    $this->load->model('queries');
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $all_salles = $this->queries->get_sallesTodayData($selected_branch_id);
    $total_sell = $this->queries->get_today_salesData($selected_branch_id);
    $total_profit = $this->queries->get_today_profit($selected_branch_id);
    $shop = $this->queries->get_shop_infoData();
    $total_retail = $this->queries->get_today_salesretail($selected_branch_id);
    $total_profit_retail = $this->queries->get_today_profitretail($selected_branch_id);
    $total_whole = $this->queries->get_today_salesWholeData($selected_branch_id);
    $whole_profit = $this->queries->get_today_profitwhole($selected_branch_id);
    
    $data_employee = $this->queries->get_sallesTodayData_seller($selected_branch_id);
      //       echo "<pre>";
      // print_r($data_employee);
      //          exit();

    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/today_sell_report',['all_salles'=>$all_salles,'total_sell'=>$total_sell,'total_profit'=>$total_profit,'shop'=>$shop,'total_retail'=>$total_retail,'total_profit_retail'=>$total_profit_retail,'total_whole'=>$total_whole,'whole_profit'=>$whole_profit,'data_employee'=>$data_employee,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }




  public function payRol(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $admin = $this->queries->get_all_seller();
      // echo "<pre>";
      // print_r($admin);
      // echo "</pre>";
      //    exit();
    $this->load->view('admin/pay_rol',['admin'=>$admin,'my'=>$my]);
  }

  public function pay($user_id){
    $this->load->model('queries');
    //$user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $pay_data = $this->queries->pay_view($user_id);
    $pay = $this->queries->pay_get($user_id);
      // print_r($pay_data);
      //     exit();
    $this->load->view('admin/pay',['pay_data'=>$pay_data,'pay'=>$pay,'my'=>$my]);
  }

  public function Delete_payrol($id)
  {
    $this->load->model('queries');
    $pay_data = $this->queries->get_editPay($id);
    $user_id = $pay_data->user_id;
    if($this->queries->delete_mishahala($id));
     $this->session->set_flashdata("massage",'Data Deleted successfully');
     return redirect('admin/pay/'.$user_id);
  }

  public function create_pay($user_id){
     $this->form_validation->set_rules('user_id','User','required');
     $this->form_validation->set_rules('pay_amount','Amount','required');
     $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
     if ($this->form_validation->run()) {
       $data = $this->input->post();
       //  echo "<pre>";
       // print_r($data);
       //  echo "</pre>";
       //    exit();
       $this->load->model('queries');
        if ($this->queries->insert_payrol($data)) {
             $this->session->set_flashdata('massage','Payment has been Done');
        }else{
           $this->session->set_flashdata('error','Failed'); 
        }
        return redirect('admin/pay/'.$user_id);
     }
     $this->pay();
  }

  public function edit_payrol($id){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $pay_data = $this->queries->get_editPay($id);
     // print_r($pay_data);
     //       exit();
    $this->load->view('admin/edit_payrol',['pay_data'=>$pay_data,'my'=>$my]);
  }

  public function modify_payrol($id){
     $this->form_validation->set_rules('pay_amount','Amount','required');
     $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
     if ($this->form_validation->run()) {
       $data = $this->input->post();
       //  echo "<pre>";
       // print_r($data);
       //  echo "</pre>";
       //    exit();
       $this->load->model('queries');
        if ($this->queries->update_payrol($data,$id)) {
             $this->session->set_flashdata('massage','Data updated successfully');
        }else{
           $this->session->set_flashdata('error','Failed'); 
        }
        return redirect('admin/edit_payrol/'.$id);
     }
     $this->edit_payrol();
  }


 

  public function payrol_report(){
    $this->load->model('queries');
    $pay_role = $this->queries->get_PayrolData();
    $mishahara_data = $this->queries->get_sumPayrol();
    $shop = $this->queries->get_shop_infoData();
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/payrol_report',['pay_role'=>$pay_role,'mishahara_data'=>$mishahara_data,'shop'=>$shop],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
  }

  public function setting(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
      // print_r($my);
      //     exit();
    $this->load->view('admin/setting',['my'=>$my]);
  }


  public function modify_mydata($user_id){
    $this->form_validation->set_rules('full_name','Name','required');
    $this->form_validation->set_rules('phone_number','Phone number','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run() ) {
      $data = [
        'full_name' => $this->input->post('full_name', true),
        'phone_number' => $this->input->post('phone_number', true),
      ];

      if (!empty($_FILES['img']['name'])) {
        $uploadPath = FCPATH . 'assets/admin/img/';
        if (!is_dir($uploadPath)) {
          @mkdir($uploadPath, 0775, true);
        }

        if (!is_writable($uploadPath)) {
          $this->session->set_flashdata('error', 'Upload destination is not writable: ' . $uploadPath);
          return redirect('admin/setting');
        }

        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('img')) {
          $uploadData = $this->upload->data();
          $data['img'] = $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
          return redirect('admin/setting');
        }
      }

      $this->load->model('queries');
      if ($this->queries->update_mydata($data,$user_id)) {
        $this->session->set_flashdata('massage','Data Updated successfully');
      }else{
        $this->session->set_flashdata('error','Failed');
      }
      return redirect('admin/setting');
    }
    $this->setting();
  }

  public function profile_pc(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $this->load->view('admin/profile_pc',['my'=>$my]);
  }



  public function modify_profilepc($user_id){
    $img = null;
    if (!empty($_FILES['img']['name'])) {
                $config['upload_path'] = 'assets/admin/img/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['img']['name'];
                    //die($config);
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('img')) {
                    $uploadData = $this->upload->data();
                    $img = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                    return redirect('admin/profile_pc');
                }
            }
            
            //Prepare array of user data
            $data = array();
            if ($img !== null) {
                $data['img'] = $img;
            }
            //   echo "<pre>";
            // print_r($data);
            //  echo "</pre>";
            //   exit();
            //Pass user data to model
           $this->load->model('queries'); 
            $data = $this->queries->update_mydata($data,$user_id);
            
            //Storing insertion status message.
            if($data){
              $this->session->set_flashdata('massage','welcome you are login');
              return redirect('admin/index');
            }else{
                $this->session->set_flashdata('error','Data failed!!');
              return redirect('admin/profile_pc');
            }
  }

  public function shop_info(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $id = 1;
    $shop_info = $this->queries->get_shop_info($id);
      // print_r($shop_info);
      //     exit();
    $this->load->view('admin/shop_information',['shop_info'=>$shop_info,'my'=>$my]);
  }

  public function modify_shop_info($id){
    $this->form_validation->set_rules('shop_name','Shop name','required');
    $this->form_validation->set_rules('po_box','po_box');
    $this->form_validation->set_rules('location','location','required');
    $this->form_validation->set_rules('phone','phone','required');
    $this->form_validation->set_rules('email','email','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $this->load->model('queries');
        $data = [
          'shop_name' => $this->input->post('shop_name', true),
          'po_box' => $this->input->post('po_box', true),
          'location' => $this->input->post('location', true),
          'phone' => $this->input->post('phone', true),
          'email' => $this->input->post('email', true),
        ];

        $logoColumns = [];
        foreach (['logo', 'shop_logo', 'image'] as $column) {
          if ($this->db->field_exists($column, 'tbl_information')) {
            $logoColumns[] = $column;
          }
        }

        if (!empty($_FILES['logo']['name'])) {
          if (empty($logoColumns)) {
            $this->session->set_flashdata('error', 'No logo column found in tbl_information. Add a logo/shop_logo/image column first.');
            return redirect('admin/shop_info');
          }

          $uploadPath = FCPATH . 'assets/admin/img/';
          if (!is_dir($uploadPath)) {
            @mkdir($uploadPath, 0775, true);
          }
          if (!is_writable($uploadPath)) {
            $this->session->set_flashdata('error', 'Upload destination is not writable: ' . $uploadPath);
            return redirect('admin/shop_info');
          }

          $config['upload_path'] = $uploadPath;
          $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
          $config['encrypt_name'] = true;

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('logo')) {
            $uploadData = $this->upload->data();
            $logoFile = $uploadData['file_name'];

            foreach ($logoColumns as $column) {
              $data[$column] = $logoFile;
            }
          } else {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            return redirect('admin/shop_info');
          }
        }

        if ($this->queries->update_shop_info($id,$data)) {
          $this->session->set_flashdata('massage','Shop information updated successfully');
        } else {
          $this->session->set_flashdata('error','Shop information update failed');
        }
        return redirect('admin/shop_info');
    }
    $this->shop_info();
  }


   public function change_password(){
        $this->load->model('queries');
       $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
        $this->form_validation->set_rules('oldpass', 'old password', 'required|matches[oldpass]');
        $this->form_validation->set_rules('newpass', 'new password', 'required');
        $this->form_validation->set_rules('passconf', 'confirm password', 'required|matches[newpass]');

        $this->form_validation->set_error_delimiters('<strong><div class="text-danger">', '</div></strong>');

        if($this->form_validation->run() == false) {
                //$this->load->view('estate/incs/header');
                //$this->load->view('estate/incs/side');
   
        $this->load->view("admin/password",['my'=>$my]);
                //$this->load->view('estate/incs/footer');
        }
        else {

            $user_id = $this->session->userdata('user_id');
            $newpass = $this->input->post('newpass');
            $this->queries->update_password_data($user_id, array('password' => sha1($newpass)));
            $this->session->set_flashdata('massage','Password changed successfully');
            redirect('admin/change_password');
        }
        }

public function password_check($oldpass)
    {
        $this->load->model('queries');
        $user_id = $this->session->userdata('user_id');
        $user = $this->queries->get_user_data($user_id);

        if($user->password !== sha1($oldpass)) {
            $this->form_validation->set_message('massage', ' {field} Password not Match');
            return false;
        }

        return true;
    }

    public function today_salesReport(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $all_salles = $this->queries->get_sallesTodayData($selected_branch_id);
    $total_sell = $this->queries->get_today_salesData($selected_branch_id);
    $total_profit = $this->queries->get_today_profit($selected_branch_id);
      $this->load->view('admin/today_salesData',['my'=>$my,'all_salles'=>$all_salles,'total_sell'=>$total_sell,'total_profit'=>$total_profit,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

    public function today_cashflowData(){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $data_cash = $this->queries->get_all_cashflow();
  $total_expences = $this->queries->get_totalEpences();
  $data_cashin = $this->queries->get_today_salesData();
  $huduma = $this->queries->get_total_service_today();
      $this->load->view('admin/today_cashflowData',['my'=>$my,'data_cash'=>$data_cash,'total_expences'=>$total_expences,'data_cashin'=>$data_cashin,'huduma'=>$huduma]);
    }

  public function general_cashflowData(){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $data = $this->queries->get_all_cashflowData();
  $all_matumiz = $this->queries->All_totalMatumizi();
  $all_sell = $this->queries->get_All_salesData();
  $mishahara_data = $this->queries->get_sumPayrol();
  $this->load->view('admin/general_cashflowData',['my'=>$my,'data'=>$data,'all_matumiz'=>$all_matumiz,'all_sell'=>$all_sell,'mishahara_data'=>$mishahara_data]);
    }

    public function all_productData(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_productAll($selected_branch_id);
      $this->load->view('admin/all_productData',['my'=>$my,'product'=>$product,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

    public function sales_productData(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_productAll($selected_branch_id);
      $this->load->view('admin/sales_price',['product'=>$product,'my'=>$my,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

    public function physical_counting_report(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_all_inventory($selected_branch_id);

      $this->load->view('admin/physical_counting_report', [
        'my' => $my,
        'product' => $product,
        'branches' => $branches,
        'selected_branch_id' => $selected_branch_id,
      ]);
    }

    public function print_physical_counting_report(){
      $this->load->model('queries');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_all_inventory($selected_branch_id);
      $shop = $this->queries->get_shop_infoData();

      $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
      $html = $this->load->view('admin/print_physical_counting_report', [
        'product' => $product,
        'shop' => $shop,
        'branches' => $branches,
        'selected_branch_id' => $selected_branch_id,
      ], true);
      $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
      $mpdf->WriteHTML($html);
      $mpdf->Output('physical-counting-report.pdf', 'D');
    }

    public function sales_profit_report(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $today = date('Y-m-d');
      $from = trim((string)$this->input->get('from', TRUE));
      $to = trim((string)$this->input->get('to', TRUE));
      $from = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) ? $from : $today;
      $to = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) ? $to : $today;

      if ($from > $to) {
        $temp = $from;
        $from = $to;
        $to = $temp;
      }

      $sales = $this->queries->get_sales_profit_report($from, $to, $selected_branch_id);
      $totals = $this->queries->get_sales_profit_report_totals($from, $to, $selected_branch_id);

      $this->load->view('admin/sales_profit_report', [
        'my' => $my,
        'branches' => $branches,
        'selected_branch_id' => $selected_branch_id,
        'sales' => $sales,
        'totals' => $totals,
        'from' => $from,
        'to' => $to,
      ]);
    }

    public function print_sales_profit_report(){
      $this->load->model('queries');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $shop = $this->queries->get_shop_infoData();
      $today = date('Y-m-d');
      $from = trim((string)$this->input->get('from', TRUE));
      $to = trim((string)$this->input->get('to', TRUE));
      $from = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) ? $from : $today;
      $to = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) ? $to : $today;

      if ($from > $to) {
        $temp = $from;
        $from = $to;
        $to = $temp;
      }

      $sales = $this->queries->get_sales_profit_report($from, $to, $selected_branch_id);
      $totals = $this->queries->get_sales_profit_report_totals($from, $to, $selected_branch_id);

      $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
      $html = $this->load->view('admin/print_sales_profit_report', [
        'shop' => $shop,
        'branches' => $branches,
        'selected_branch_id' => $selected_branch_id,
        'sales' => $sales,
        'totals' => $totals,
        'from' => $from,
        'to' => $to,
      ], true);
      $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
      $mpdf->WriteHTML($html);
      $mpdf->Output('sales-profit-report.pdf', 'D');
    }

    public function empty_productData(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $empty_prdData = $this->queries->get_emptyProduct($selected_branch_id);
      $this->load->view('admin/empty_productData',['empty_prdData'=>$empty_prdData,'my'=>$my,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

    public function retail_sales(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $retail_data = $this->queries->get_sallesTodayDataRetail($selected_branch_id);
      $total_retail = $this->queries->get_today_salesretail($selected_branch_id);
      $total_profit_retail = $this->queries->get_today_profitretail($selected_branch_id);
      // echo "<pre>";
      // print_r($total_profit_retail);
      // echo "</pre>";
      //        exit();
      $this->load->view('admin/retail_sales',['my'=>$my,'retail_data'=>$retail_data,'total_retail'=>$total_retail,'total_profit_retail'=>$total_profit_retail,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }


    public function print_retail_report(){
      $this->load->model('queries');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $retail_data = $this->queries->get_sallesTodayDataRetail($selected_branch_id);
      $total_retail = $this->queries->get_today_salesretail($selected_branch_id);
      $total_profit_retail = $this->queries->get_today_profitretail($selected_branch_id);
      $shop = $this->queries->get_shop_infoData();

      $mpdf = new \Mpdf\Mpdf();
      $html = $this->load->view('admin/retail_report',['retail_data'=>$retail_data,'total_retail'=>$total_retail,'total_profit_retail'=>$total_profit_retail,'shop'=>$shop,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
      $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }

    public function whole_sale(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $whole_sale = $this->queries->get_sallesTodayWholesaleData();
      $my = $this->queries->get_mydata($user_id);
      $total_whole = $this->queries->get_today_salesWholeData();
      $whole_profit = $this->queries->get_today_profitwhole();
        // echo "<pre>";
        //  print_r($whole_profit);
        //  echo "</pre>";
        //        exit();
      $this->load->view('admin/whore_sales',['whole_sale'=>$whole_sale,'my'=>$my,'total_whole'=>$total_whole,'whole_profit'=>$whole_profit]);
    }

    public function print_whole_sale(){
      $this->load->model('queries');
      $whole_sale = $this->queries->get_sallesTodayWholesaleData();
      $total_whole = $this->queries->get_today_salesWholeData();
      $whole_profit = $this->queries->get_today_profitwhole();
      $shop = $this->queries->get_shop_infoData();

      $mpdf = new \Mpdf\Mpdf();
      $html = $this->load->view('admin/whole_report',['whole_sale'=>$whole_sale,'total_whole'=>$total_whole,'whole_profit'=>$whole_profit,'shop'=>$shop],true);
      $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }


    public function privious_data(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $from = $this->input->post('from', true) ?: $this->input->get('from', true);
    $to = $this->input->post('to', true) ?: $this->input->get('to', true);
    $sell_status = $this->input->post('sell_status', true) ?: $this->input->get('sell_status', true);

    if (!$from || !$to || !$sell_status) {
      $this->load->view('admin/previous_data',[
        'my'=>$my,
        'data'=>[],
        'from'=>'',
        'to'=>'',
        'sell_status'=>'',
        'total_selldata'=>(object)['total_sell_data'=>0],
        'total_profit'=>(object)['total_profitData'=>0],
      ]);
      return;
    }

    $from = date('Y-m-d', strtotime($from));
    $to = date('Y-m-d', strtotime($to));
    if ($from > $to) {
      $tmp = $from;
      $from = $to;
      $to = $tmp;
    }

    $data = $this->queries->search_mauzoWhole_retail($from,$to,$sell_status);
    $total_selldata = $this->queries->search_mauzoPitaData($from,$to,$sell_status);
    $total_profit = $this->queries->search_profitData($from,$to,$sell_status);
       // print_r($total_profit);
       //        exit();

    $this->load->view('admin/previous_data',['my'=>$my,'data'=>$data,'from'=>$from,'to'=>$to,'sell_status'=>$sell_status,'total_selldata'=>$total_selldata,'total_profit'=>$total_profit]);
    }

    public function print_previousData($from,$to,$sell_status){
    $this->load->model('queries');
    $from = date('Y-m-d', strtotime($from));
    $to = date('Y-m-d', strtotime($to));
    if ($from > $to) {
      $tmp = $from;
      $from = $to;
      $to = $tmp;
    }
    $data = $this->queries->search_mauzoWhole_retail($from,$to,$sell_status);
    $total_selldata = $this->queries->search_mauzoPitaData($from,$to,$sell_status);
    $total_profit = $this->queries->search_profitData($from,$to,$sell_status);
    $shop = $this->queries->get_shop_infoData();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        //     exit();
     $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/previous_data_report',['shop'=>$shop,'data'=>$data,'from'=>$from,'to'=>$to,'total_selldata'=>$total_selldata,'total_profit'=>$total_profit,'sell_status'=>$sell_status],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
    $mpdf->WriteHTML($html);
    $mpdf->Output();

    }

    public function buying_price(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $all_product = $this->queries->get_buy_price();
      // echo "<pre>";
      //   print_r($all_product);
      //   echo "</pre>";
      //        exit();
      $this->load->view('admin/buy_price',['my'=>$my,'all_product'=>$all_product]);
    }

    public function print_buyingprice(){
      $this->load->model('queries');
      $all_product = $this->queries->get_buy_price();
      $shop = $this->queries->get_shop_infoData();
       $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/buy_price_report',['shop'=>$shop,'all_product'=>$all_product],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    }


    public function admin_sell(){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $branch_id = $this->current_admin_branch_id();
  $datay = $this->queries->get_productAll($branch_id);
  $cartItems = $this->cart->contents();
  $cart_product_ids = array_column($cartItems, 'id');
  $discount_rules = $this->queries->get_active_discount_rules($branch_id);
  $product_categories = $this->queries->get_product_category_map($cart_product_ids);
  $limit = $this->queries->get_stock_limitData();
  $kwisha = $this->queries->get_bidhaa_kwisha($branch_id);
  $this->load->view('admin/sell',['datay'=>$datay,'cartItems'=>$cartItems,'limit'=>$limit,'my'=>$my,'kwisha'=>$kwisha,'selected_branch_id'=>$branch_id,'discount_rules'=>$discount_rules,'product_categories'=>$product_categories]);
    }



  
  function addToCart($proID){
        $this->load->model('queries');
        $branch_id = $this->current_admin_branch_id();
        // Fetch specific product by ID
        $product = $this->queries->getRows($proID);
        if (!$product || ($branch_id && (int)($product['branch_id'] ?? 0) !== (int)$branch_id)) {
          $this->session->set_flashdata('error', 'This product is not available in the selected branch.');
          return redirect('admin/admin_sell');
        }
        $branch_sql = $branch_id ? " AND branch_id = '".(int)$branch_id."'" : "";
        $stockRow = $this->db->query("SELECT balance FROM tbl_store WHERE product_id = '".$product['id']."' $branch_sql LIMIT 1")->row_array();
        $stockBalance = $stockRow ? (int) $stockRow['balance'] : 0;
        
        // Add product to the cart
        $data = array(
            'id'  => $product['id'],
            'qty'  => 1,
            'price' => $product['price'],
            'ju_price' => $product['ju_price'],
            'buy_price' => $product['buy_price'],
            'name'    => $product['name'],
            'category' => isset($product['category']) ? $product['category'] : '',
            'bland' => $product['bland'],
            'exp_date' => $product['exp_date'],
            'unit' => $product['unit'],
            'stock_limit' => $product['stock_limit'],
            'stock_balance' => $stockBalance,
        );
        // echo "<pre>";
        // print_r($data);
        //  echo "</pre>";
        //      exit();
        $this->cart->insert($data);
        
        // Redirect to the cart page
      redirect('admin_cart/');
    }


      function addToCart_jumla($proID){
        $this->load->model('queries');
        $branch_id = $this->current_admin_branch_id();
        // Fetch specific product by ID
        $product = $this->queries->getRows($proID);
        if (!$product || ($branch_id && (int)($product['branch_id'] ?? 0) !== (int)$branch_id)) {
          $this->session->set_flashdata('error', 'This product is not available in the selected branch.');
          return redirect('admin/admin_sell');
        }
        $branch_sql = $branch_id ? " AND branch_id = '".(int)$branch_id."'" : "";
        $stockRow = $this->db->query("SELECT balance FROM tbl_store WHERE product_id = '".$product['id']."' $branch_sql LIMIT 1")->row_array();
        $stockBalance = $stockRow ? (int) $stockRow['balance'] : 0;
        
        // Add product to the cart
        $data = array(
            'id'  => $product['id'],
            'qty'  => 1,
            'price' => $product['price'],
            'ju_price' => $product['ju_price'],
            'buy_price' => $product['buy_price'],
            'name'    => $product['name'],
            'category' => isset($product['category']) ? $product['category'] : '',
            'unit' => $product['unit'],
            'stock_balance' => $stockBalance,
        );
        // echo "<pre>";
        // print_r($data);
        //  echo "</pre>";
        //      exit();
        $this->cart->insert($data);
        
        // Redirect to the cart page
      redirect('admin_cart_jumla/');
    }



   function updateItemQty(){
        $update = 0;
        
        // Get cart item info
        $rowid = $this->input->get('rowid');
        $item_id = $this->input->get('item_id');
        $qty = $this->input->get('qty');
        //$id = $this->input->get();
          // print_r($rowid);
          //     exit();
        // Update item in the cart
        if(!empty($rowid) && !empty($qty)){
            $data = array(
                'rowid' => $rowid,
                'qty'   => $qty
            );

           // alert($data);
           if($this->checkForItemBalance($item_id,$qty)){

            $update = $this->cart->update($data);
              // print_r($update);
              //   exit();
              echo "ok";
        }else{
          echo "err";
        }


        }
        
        // Return response
        // echo $update?'ok':'err';
    }

    function checkForItemBalance($item_id,$qnty){
      $branch_id = $this->current_admin_branch_id();
      $branch_sql = $branch_id ? " AND branch_id = '".(int)$branch_id."'" : "";
      $sql = "SELECT * FROM tbl_store WHERE product_id='$item_id' $branch_sql AND balance >= '$qnty'";
      $data = $this->db->query($sql);
      
      if(count($data->result()) > 0){
        return true;
      }
      return false;
    }





    //get sell now function
    public function getsdata($user_id){
  $sql = "SELECT s.quantity,s.product_id FROM tbl_sell s JOIN tbl_user u ON u.user_id = s.user_id  WHERE s.user_id = '$user_id' AND s.created_at = NOW() ";
  $query = $this->db->query($sql);

   return $query->result();
    }





 public function sell(){
  $this->load->model('queries');
  $this->load->library('discountservice');
      $validation  = array( array('field'=> 'product_id[]','rules'=>'required'));
      $this->form_validation->set_rules($validation);
       if ($this->form_validation->run() == true) {
          $product_id  = $this->input->post('product_id[]');
          $quantity  = $this->input->post('quantity[]');
          $new_sell_price = $this->input->post('new_sell_price[]');
          $discount_amount = $this->input->post('discount_amount[]');
          $cart_discount_amount = (float)$this->input->post('cart_discount_amount');
          $total_sell_price = $this->input->post('total_sell_price[]');
          $profit = $this->input->post('profit[]');
          $user_id = $this->input->post('user_id[]');
          $sell_status = $this->input->post('sell_status[]');
          $customer = $this->input->post('customer');
          $total_price = $this->input->post('total_price');
          $sell_day = date("Y-m-d");
          $date_recept = date("Y-m-d H:i:s");
          $branch_id = $this->current_admin_branch_id();
          $discounted_total_price = 0;
          $original_cart_total = $this->discountservice->originalCartTotal($quantity, $new_sell_price);
          $sale_items = $this->discountservice->cartLineItems($product_id, $quantity, $new_sell_price);
          $cart_discount = $this->discountservice->applyCartTotalDiscount($sale_items, $cart_discount_amount, $branch_id, $user_id[0] ?? null, $original_cart_total);
          if ($cart_discount_amount > 0 && !empty($cart_discount['blocked_reason'])) {
            $this->session->set_flashdata('error', $cart_discount['blocked_reason']);
            return redirect('admin_cart/');
          }
          $cart_discount_allocations = $cart_discount['allocations'];
          $used_discount_by_rule = [];

          // print_r($sell_status);
          //      exit();
          $order_id = $this->insert_recept_number($date_recept);
          for($i=0; $i<count($product_id);$i++){
            $branch_sql = $branch_id ? " AND branch_id = '".(int)$branch_id."'" : "";
            $allowed = $this->db->query("SELECT id, buy_price, branch_id FROM product WHERE id = '".(int)$product_id[$i]."' $branch_sql")->row();
            if (!$allowed) {
              $this->session->set_flashdata('error', 'One or more products are not available in the selected branch.');
              return redirect('admin/admin_sell');
            }
            $sale_branch_id = $branch_id ? (int)$branch_id : (int)$allowed->branch_id;
            $qty_value = (float) $quantity[$i];
            $sell_price_value = (float) $new_sell_price[$i];
            $manual_discount_value = isset($discount_amount[$i]) ? (float)$discount_amount[$i] : 0;
            $discount = $this->discountservice->applyManualDiscount($product_id[$i], $qty_value, $sell_price_value, $manual_discount_value, $sale_branch_id, $user_id[$i], $original_cart_total, $used_discount_by_rule);
            if ($manual_discount_value > 0 && !empty($discount['blocked_reason'])) {
              $this->session->set_flashdata('error', $discount['blocked_reason']);
              return redirect('admin_cart/');
            }
            if (!empty($discount['discount_id'])) {
              $rule_id = (int)$discount['discount_id'];
              $used_discount_by_rule[$rule_id] = isset($used_discount_by_rule[$rule_id]) ? $used_discount_by_rule[$rule_id] + (float)$discount['discount_amount'] : (float)$discount['discount_amount'];
            }
            $cart_discount_value = isset($cart_discount_allocations[$i]) ? (float)$cart_discount_allocations[$i] : 0;
            $line_total_before_discount = $sell_price_value * $qty_value;
            $total_line_discount = min($line_total_before_discount, (float)$discount['discount_amount'] + $cart_discount_value);
            $total_sell_value = $line_total_before_discount - $total_line_discount;
            $final_sell_price_value = $qty_value > 0 ? $total_sell_value / $qty_value : $sell_price_value;
            $profit_value = ($final_sell_price_value - (float) $allowed->buy_price) * $qty_value;
            $discounted_total_price += $total_sell_value;
            $date = date("Y-m-d");
       $this->db->query("INSERT INTO  tbl_sell (`product_id`,`quantity` ,`new_sell_price`,`total_sell_price`,`profit`,`user_id`,`branch_id`,`sell_status`,`sell_day`,`customer`) 
      VALUES ('".$product_id[$i]."','".$qty_value."','".$final_sell_price_value."','".$total_sell_value."','".$profit_value."','".$user_id[$i]."','".$sale_branch_id."','".$sell_status[$i]."','$sell_day','$customer')");
        $sell_id = $this->db->insert_id();
        $this->discountservice->saveAudit([
          'discount_id' => $cart_discount_value > 0 ? $cart_discount['discount_id'] : $discount['discount_id'],
          'transaction_id' => $sell_id,
          'product_id' => $product_id[$i],
          'applied_by_user' => $user_id[$i],
          'approved_by' => $discount['approved_by'],
          'original_price' => $sell_price_value,
          'discount_amount' => $total_line_discount,
          'final_price' => $final_sell_price_value,
          'quantity' => $qty_value,
        ]);
   
         
        $this->record_stock_movement($product_id[$i], $quantity[$i], $user_id[$i], 'SOLD', $date);
      // return  $this->db->insert_id();
      //  print_r($stock_id);
      //          exit();
          }

           for ($i=0; $i<count($product_id); $i++) { 
        $this->update_storedata($product_id[$i],$quantity[$i]);
              //print_r($data[$i]->product_id);
              //print_r($data[$i]->quantity);
            }

          $this->insert_cashire_report($customer,$discounted_total_price);
          $this->session->set_flashdata('massage','The product sold successfully');
          
       }
       $cartItems = $this->cart->contents();
          $shop = $this->queries->get_shop_infoData();
          $this->load->view('seller/recept',['cartItems'=>$cartItems,'shop'=>$shop,'customer'=>$customer]); 
          echo '<script type="text/javascript">window.print();  setTimeout(function(){
  window.location.href = document.referrer;
}, 2000);</script>';
           $this->cart->destroy();
       //return redirect("admin_cart/");
       }

       public function insert_recept_number($date_recept){
       $this->db->query("INSERT INTO  tbl_receipt (`date_receipt`) VALUES ('$date_recept')");
       return $this->db->insert_id();
       }



     public function sell_jumla(){
        $this->load->model('queries');
        $this->load->library('discountservice');
      $validation  = array( array('field'=> 'product_id[]','rules'=>'required'));
      $this->form_validation->set_rules($validation);
       if ($this->form_validation->run() == true) {
          $product_id  = $this->input->post('product_id[]');
          $quantity  = $this->input->post('quantity[]');
          $new_sell_price = $this->input->post('new_sell_price[]');
          $discount_amount = $this->input->post('discount_amount[]');
          $cart_discount_amount = (float)$this->input->post('cart_discount_amount');
          $total_sell_price = $this->input->post('total_sell_price[]');
          $profit = $this->input->post('profit[]');
          $user_id = $this->input->post('user_id[]');
          $customer = $this->input->post('customer');
          $sell_status = $this->input->post('sell_status[]');
          $total_price = $this->input->post('total_price');
          $sell_day = date("Y-m-d");
          $branch_id = $this->current_admin_branch_id();
          $discounted_total_price = 0;
          $original_cart_total = $this->discountservice->originalCartTotal($quantity, $new_sell_price);
          $sale_items = $this->discountservice->cartLineItems($product_id, $quantity, $new_sell_price);
          $cart_discount = $this->discountservice->applyCartTotalDiscount($sale_items, $cart_discount_amount, $branch_id, $user_id[0] ?? null, $original_cart_total);
          if ($cart_discount_amount > 0 && !empty($cart_discount['blocked_reason'])) {
            $this->session->set_flashdata('error', $cart_discount['blocked_reason']);
            return redirect('admin_cart_jumla/');
          }
          $cart_discount_allocations = $cart_discount['allocations'];
          $used_discount_by_rule = [];

          // print_r($sell_status);
          //      exit();
           $date_recept = date("Y-m-d H:i:s");
           $order_id = $this->insert_recept_number($date_recept);
          for($i=0; $i<count($product_id);$i++){
            $branch_sql = $branch_id ? " AND branch_id = '".(int)$branch_id."'" : "";
            $allowed = $this->db->query("SELECT id, buy_price, branch_id FROM product WHERE id = '".(int)$product_id[$i]."' $branch_sql")->row();
            if (!$allowed) {
              $this->session->set_flashdata('error', 'One or more products are not available in the selected branch.');
              return redirect('admin/admin_sell');
            }
        $sale_branch_id = $branch_id ? (int)$branch_id : (int)$allowed->branch_id;
        $qty_value = (float) $quantity[$i];
        $sell_price_value = (float) $new_sell_price[$i];
        $manual_discount_value = isset($discount_amount[$i]) ? (float)$discount_amount[$i] : 0;
        $discount = $this->discountservice->applyManualDiscount($product_id[$i], $qty_value, $sell_price_value, $manual_discount_value, $sale_branch_id, $user_id[$i], $original_cart_total, $used_discount_by_rule);
        if ($manual_discount_value > 0 && !empty($discount['blocked_reason'])) {
          $this->session->set_flashdata('error', $discount['blocked_reason']);
          return redirect('admin_cart_jumla/');
        }
        if (!empty($discount['discount_id'])) {
          $rule_id = (int)$discount['discount_id'];
          $used_discount_by_rule[$rule_id] = isset($used_discount_by_rule[$rule_id]) ? $used_discount_by_rule[$rule_id] + (float)$discount['discount_amount'] : (float)$discount['discount_amount'];
        }
        $cart_discount_value = isset($cart_discount_allocations[$i]) ? (float)$cart_discount_allocations[$i] : 0;
        $line_total_before_discount = $sell_price_value * $qty_value;
        $total_line_discount = min($line_total_before_discount, (float)$discount['discount_amount'] + $cart_discount_value);
        $total_sell_value = $line_total_before_discount - $total_line_discount;
        $final_sell_price_value = $qty_value > 0 ? $total_sell_value / $qty_value : $sell_price_value;
        $profit_value = ($final_sell_price_value - (float) $allowed->buy_price) * $qty_value;
        $discounted_total_price += $total_sell_value;
        $this->db->query("INSERT INTO  tbl_sell (`product_id`,`quantity` ,`new_sell_price`,`total_sell_price`,`profit`,`user_id`,`branch_id`,`sell_status`,`sell_day`,`customer`) 
      VALUES ('".$product_id[$i]."','".$qty_value."','".$final_sell_price_value."','".$total_sell_value."','".$profit_value."','".$user_id[$i]."','".$sale_branch_id."','".$sell_status[$i]."','$sell_day','$customer')");
    $sell_id = $this->db->insert_id();
    $this->discountservice->saveAudit([
      'discount_id' => $cart_discount_value > 0 ? $cart_discount['discount_id'] : $discount['discount_id'],
      'transaction_id' => $sell_id,
      'product_id' => $product_id[$i],
      'applied_by_user' => $user_id[$i],
      'approved_by' => $discount['approved_by'],
      'original_price' => $sell_price_value,
      'discount_amount' => $total_line_discount,
      'final_price' => $final_sell_price_value,
      'quantity' => $qty_value,
    ]);
    $this->record_stock_movement($product_id[$i], $quantity[$i], $user_id[$i], 'SOLD', $sell_day);
          }
          
           for ($i=0; $i<count($product_id); $i++) { 
        $this->update_storedata($product_id[$i],$quantity[$i]);
              //print_r($data[$i]->product_id);
              //print_r($data[$i]->quantity);
            }  
          $this->insert_cashire_report($customer,$discounted_total_price);
          $this->session->set_flashdata('massage','The product sold successfully');
          //$this->cart->destroy();
       }

          $cartItems = $this->cart->contents();
          $shop = $this->queries->get_shop_infoData();
          $this->load->view('seller/recept_jumla',['cartItems'=>$cartItems,'shop'=>$shop,'customer'=>$customer]); 
          echo '<script type="text/javascript">window.print();  setTimeout(function(){
  window.location.href = document.referrer;
}, 2000);</script>';
           $this->cart->destroy();
       //return redirect("admin_cart/"); 
    }

//update product store
  public function update_storedata($product_id,$quantity){
  $branch_id = $this->current_admin_branch_id();
  $branch_sql = $branch_id ? " AND `branch_id` = '".(int)$branch_id."'" : "";
  $sqldata="UPDATE tbl_store SET `out_balance`= `out_balance`+$quantity,`balance`=`balance`-$quantity WHERE `product_id` = '$product_id' $branch_sql";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

//        //update product store
//   public function update_storeDatas($product_id,$quantity){
//   for ($i=0; $i<count($product_id); $i++) { 
//   $sqldata="UPDATE tbl_store SET `out_balance`= `out_balance`+'".$quantity[$i]."',`balance`=`balance`'".-$quantity[$i]."' WHERE `product_id` = '".$product_id[$i]."'";
//     // print_r($sqldata);
//     //    exit();
//   $query = $this->db->query($sqldata);
//    return true;
//    }
// }



       //get sell now function
 public function gets($user_id){
  $sql = "SELECT s.quantity,s.product_id FROM tbl_sell s JOIN tbl_user u ON u.user_id = s.user_id  WHERE s.user_id = '$user_id' AND s.created_at = NOW() ";
  $query = $this->db->query($sql);
   return $query->result();
    } 
    

    public function view_product_movement(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $product_id = $this->input->post('product_id');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_store_product_available($selected_branch_id);
      $data = $this->queries->view_stock_movement($product_id);
      $privillage = $this->queries->get_userPrivillage($user_id);

      $trending_product = $this->queries->get_product_tranding($selected_branch_id);
       // echo "<pre>";
       //  print_r($trending_product);
       //        exit();
      $this->load->view('admin/product_movement',['my'=>$my,'product'=>$product,'data'=>$data,'privillage'=>$privillage,'trending_product'=>$trending_product,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

    public function stock_balance_history(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $privillage = $this->queries->get_userPrivillage($user_id);
      $product_id = $this->input->get('product_id');
      $selected_product_id = ($product_id !== null && $product_id !== '') ? (int)$product_id : null;
      $selected_branch_id = $this->current_admin_branch_id();

      $branches = $this->queries->get_branches();
      $product = $this->queries->get_store_product_available($selected_branch_id);

      $this->load->view('admin/stock_balance_history',[
        'my' => $my,
        'privillage' => $privillage,
        'product' => $product,
        'selected_product_id' => $selected_product_id,
        'branches' => $branches,
        'selected_branch_id' => $selected_branch_id,
      ]);
    }

    public function product_stock_history($product_id = null){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $privillage = $this->queries->get_userPrivillage($user_id);

      $selected_product_id = (int)$product_id;
      if ($selected_product_id <= 0) {
        $this->session->set_flashdata('error', 'Invalid product selected');
        return redirect('admin/stock_balance_history');
      }

      $from = trim((string)$this->input->get('from'));
      $to = trim((string)$this->input->get('to'));
      $from_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) ? $from : '';
      $to_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) ? $to : '';

      $product_data = $this->queries->get_product_safe($selected_product_id);
      if (!$product_data) {
        $this->session->set_flashdata('error', 'Product not found');
        return redirect('admin/stock_balance_history');
      }

      $history = $this->queries->get_stock_balance_history($selected_product_id, $from_date, $to_date);
      $totals = $this->queries->get_stock_movement_totals($selected_product_id, $from_date, $to_date);
      $daily_summary = $this->queries->get_stock_movement_daily_summary($selected_product_id, $from_date, $to_date);

      $opening_balance = 0;
      if (!empty($totals)) {
        $opening_balance = (int)$totals[0]->current_balance - (int)$totals[0]->net_movement;
      }

      $running_balance = $opening_balance;
      for ($i = 0; $i < count($history); $i++) {
        $status = strtoupper(trim((string)$history[$i]->mov_status));
        $qty = (int)$history[$i]->product_qnty;
        $is_out = in_array($status, array('SOLD', 'ADJUSTED OUT'), true);
        $qty_in = $is_out ? 0 : $qty;
        $qty_out = $is_out ? $qty : 0;

        $running_balance += ($qty_in - $qty_out);
        $history[$i]->qty_in = $qty_in;
        $history[$i]->qty_out = $qty_out;
        $history[$i]->running_balance = $running_balance;
      }

      $this->load->view('admin/product_stock_history',[
        'my' => $my,
        'privillage' => $privillage,
        'product_data' => $product_data,
        'history' => $history,
        'daily_summary' => $daily_summary,
        'from_date' => $from_date,
        'to_date' => $to_date,
      ]);
    }

    public function print_product_stock_history($product_id = null){
      $this->load->model('queries');
      $selected_product_id = (int)$product_id;
      if ($selected_product_id <= 0) {
        return redirect('admin/stock_balance_history');
      }

      $from = trim((string)$this->input->get('from'));
      $to = trim((string)$this->input->get('to'));
      $from_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) ? $from : '';
      $to_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) ? $to : '';

      $product_data = $this->queries->get_product_safe($selected_product_id);
      if (!$product_data) {
        return redirect('admin/stock_balance_history');
      }

      $shop = $this->queries->get_shop_infoData();
      $history = $this->queries->get_stock_balance_history($selected_product_id, $from_date, $to_date);
      $totals = $this->queries->get_stock_movement_totals($selected_product_id, $from_date, $to_date);
      $daily_summary = $this->queries->get_stock_movement_daily_summary($selected_product_id, $from_date, $to_date);

      $opening_balance = 0;
      if (!empty($totals)) {
        $opening_balance = (int)$totals[0]->current_balance - (int)$totals[0]->net_movement;
      }

      $running_balance = $opening_balance;
      for ($i = 0; $i < count($history); $i++) {
        $status = strtoupper(trim((string)$history[$i]->mov_status));
        $qty = (int)$history[$i]->product_qnty;
        $is_out = in_array($status, array('SOLD', 'ADJUSTED OUT'), true);
        $qty_in = $is_out ? 0 : $qty;
        $qty_out = $is_out ? $qty : 0;

        $running_balance += ($qty_in - $qty_out);
        $history[$i]->qty_in = $qty_in;
        $history[$i]->qty_out = $qty_out;
        $history[$i]->running_balance = $running_balance;
      }

      $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
      $html = $this->load->view('admin/print_product_stock_history',[
        'shop' => $shop,
        'product_data' => $product_data,
        'history' => $history,
        'daily_summary' => $daily_summary,
        'from_date' => $from_date,
        'to_date' => $to_date,
      ], true);
      $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
      $mpdf->WriteHTML($html);
      $mpdf->Output();
    }


    public function filter_product_tranding(){
       $this->load->model('queries');
       $user_id = $this->session->userdata('user_id');
       $my = $this->queries->get_mydata($user_id);
       $privillage = $this->queries->get_userPrivillage($user_id);
       $from = $this->input->post('from');
       $to = $this->input->post('to');
       $branch_id = $this->input->post('branch_id');
       if ($branch_id !== null) {
         if ($branch_id === '') {
           $this->session->unset_userdata('admin_branch_id');
           $selected_branch_id = null;
         } else {
           $selected_branch_id = (int)$branch_id;
           $this->session->set_userdata('admin_branch_id', $selected_branch_id);
         }
       } else {
         $selected_branch_id = $this->current_admin_branch_id();
       }
       $branches = $this->queries->get_branches();
       $tranding = $this->queries->get_product_tranding_data($from,$to,$selected_branch_id);
          //        echo "<pre>";
          // print_r($tranding);
          //          exit();
      $this->load->view('admin/tranding_filter',['tranding'=>$tranding,'my'=>$my,'privillage'=>$privillage,'from'=>$from,'to'=>$to,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }


    public function print_tranding($from,$to){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $shop = $this->queries->get_shop_infoData();
    $selected_branch_id = $this->current_admin_branch_id();
    $branches = $this->queries->get_branches();
    $tranding = $this->queries->get_product_tranding_data($from,$to,$selected_branch_id);
    
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/print_tranding',['shop'=>$shop,'tranding'=>$tranding,'from'=>$from,'to'=>$to,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    // public function create_stock_movement($product_id){
    //   $this->load->model('queries');
    //   $data = $this->queries->view_stock_movement($product_id);
        
    //   $this->load->view('admin/product_movement')
    // }



    

    private function require_admin_role(){
      if ($this->session->userdata('role') !== 'admin') {
        $this->session->set_flashdata('error', 'Only admin can manage discounts.');
        return false;
      }

      return true;
    }

    public function discounts(){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $discounts = $this->queries->get_discount_rules();
      $this->load->view('admin/discounts', ['my' => $my, 'discounts' => $discounts]);
    }

    public function create_discount(){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      $this->save_discount_rule();
    }

    public function edit_discount($discount_id){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $discount = $this->queries->get_discount_rule($discount_id);
      if (!$discount) {
        $this->session->set_flashdata('error', 'Discount rule not found.');
        return redirect('admin/discounts');
      }
      $this->load->view('admin/discount_form', $this->discount_form_data($my, $discount));
    }

    public function update_discount($discount_id){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      $this->save_discount_rule($discount_id);
    }

    public function delete_discount($discount_id){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      if ($this->queries->delete_discount_rule($discount_id)) {
        $this->session->set_flashdata('massage', 'Discount deleted successfully.');
      } else {
        $this->session->set_flashdata('error', 'Failed to delete discount.');
      }
      return redirect('admin/discounts');
    }

    public function discount_audit(){
      if (!$this->require_admin_role()) {
        return redirect('admin/index');
      }
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $audit = $this->queries->get_discount_audit();
      $this->load->view('admin/discount_audit', ['my' => $my, 'audit' => $audit]);
    }

    private function save_discount_rule($discount_id = null){
      $this->form_validation->set_rules('discount_name', 'Discount name', 'required');
      $this->form_validation->set_rules('discount_type', 'Discount type', 'required|in_list[percentage,fixed]');
      $this->form_validation->set_rules('discount_basis', 'Calculate discount from', 'required|in_list[line,cart]');
      $this->form_validation->set_rules('applies_to', 'Applies to', 'required|in_list[product,category]');
      $this->form_validation->set_rules('discount_value', 'Discount value', 'required|numeric');
      $this->form_validation->set_rules('start_date', 'Start date', 'required');
      $this->form_validation->set_rules('end_date', 'End date', 'required');
      $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $discount = $discount_id ? $this->queries->get_discount_rule($discount_id) : null;

      if ($this->form_validation->run()) {
        $applies_to = $this->input->post('applies_to', TRUE);
        $data = [
          'discount_name' => $this->input->post('discount_name', TRUE),
          'discount_type' => $this->input->post('discount_type', TRUE),
          'discount_basis' => $this->input->post('discount_basis', TRUE) === 'cart' ? 'cart' : 'line',
          'applies_to' => $applies_to,
          'discount_value' => (float)$this->input->post('discount_value', TRUE),
          'product_id' => null,
          'category' => $applies_to === 'category' ? $this->input->post('category', TRUE) : null,
          'brand' => null,
          'customer_group' => null,
          'branch_id' => $this->input->post('branch_id', TRUE) !== '' ? (int)$this->input->post('branch_id', TRUE) : null,
          'min_purchase_amount' => (float)$this->input->post('min_purchase_amount', TRUE),
          'max_discount_per_transaction' => null,
          'limit_per_customer' => null,
          'start_date' => $this->input->post('start_date', TRUE),
          'end_date' => $this->input->post('end_date', TRUE),
          'start_time' => null,
          'end_time' => null,
          'requires_manager_approval' => 0,
          'allow_below_min_price' => 0,
          'status' => $this->input->post('status', TRUE) === 'inactive' ? 'inactive' : 'active',
        ];

        if ($applies_to === 'product' && !$this->input->post('all_products')) {
          $product_ids = (array)$this->input->post('product_ids');
          $product_ids = array_values(array_filter(array_map('intval', $product_ids)));
          if (empty($product_ids)) {
            $this->session->set_flashdata('error', 'Select at least one product or choose All products.');
            return redirect($discount_id ? 'admin/edit_discount/'.$discount_id : 'admin/create_discount');
          }
        } else {
          $product_ids = [null];
        }

        if ($discount_id) {
          $data['product_id'] = $applies_to === 'product' ? $product_ids[0] : null;
          $data['updated_at'] = date('Y-m-d H:i:s');
          $this->queries->update_discount_rule($discount_id, $data);
          $this->session->set_flashdata('massage', 'Discount updated successfully.');
        } else {
          $data['created_by'] = $user_id;
          foreach ($product_ids as $pid) {
            $row = $data;
            $row['product_id'] = $applies_to === 'product' ? $pid : null;
            $this->queries->insert_discount_rule($row);
          }
          $this->session->set_flashdata('massage', 'Discount created successfully.');
        }

        return redirect('admin/discounts');
      }

      $this->load->view('admin/discount_form', $this->discount_form_data($my, $discount));
    }

    private function discount_form_data($my, $discount = null){
      return [
        'my' => $my,
        'discount' => $discount,
        'products' => $this->queries->get_productAll(),
        'branches' => $this->queries->get_branches(),
        'categories' => $this->queries->get_product_categories(),
        'brands' => $this->queries->get_product_brands(),
      ];
    }

    //insert cashire record
     public function insert_cashire_report($customer,$total_price){
    $date = date("Y-m-d");
      $this->db->query("INSERT INTO tbl_cashire (`full_name`,`total_price`,`date`) VALUES ('$customer','$total_price','$date')");
      }


/*    public function print_recept($customer){
    $this->load->model('queries');
    $cartItems = $this->cart->contents();
    $shop = $this->queries->get_shop_infoData();
     //   echo "<pre>";
     // print_r( $text);
     // echo "</pre>";
     //     exit();
    $mpdf = new \Mpdf\Mpdf();
     // echo anchor('http://your.link.com/whatever', 'title="My News"', array('target' => '_blank', 'class' => 'new_window'));
    $html = $this->load->view('admin/recept',['shop'=>$shop,'cartItems'=>$cartItems,'customer'=>$customer,'class' => 'new_window'],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
         
    }*/




    

    public function delete_mistake_sell($sell_id){
      $this->load->model('queries');
      $mistake = $this->queries->get_mistake_data($sell_id);
        //$balance = $mistake->balance;
        //$out_balance = $mistake->out_balance;
        $product_id = $mistake->product_id;
        $quantity = $mistake->quantity;
      // echo "<pre>";
      //    print_r($quantity);
      //    print_r($product_id);
      //    echo "</pre>";
      //          exit();
         $this->update_storemistake($product_id,$quantity);
      if ($this->queries->remove_mistake($sell_id));
         $this->session->set_flashdata('massage','Sales Mistake Removed successfully ');
         return redirect('admin/index'); 
      
    }


    //update product store
  public function update_storemistake($product_id,$quantity){
  $sqldata="UPDATE tbl_store SET `out_balance`= `out_balance`-$quantity,`balance`=`balance`+$quantity WHERE `product_id` = '$product_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
   return true;
}

public function import_execel(){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $product = $this->queries->get_product();
  $this->load->view('admin/import_excell',['my'=>$my,'product'=>$product]);
}


public function import_product(){
  if (empty($_FILES['attachment']['name'])) {
    $this->session->set_flashdata('error', 'Please choose an Excel or CSV file.');
    return redirect('admin/product');
  }

  $upload_path = FCPATH . 'uploads/product_imports/';
  if (!is_dir($upload_path)) {
    @mkdir($upload_path, 0775, true);
  }

  $config['upload_path'] = $upload_path;
  $config['allowed_types'] = 'xls|xlsx|csv';
  $config['encrypt_name'] = true;

  $this->load->library('upload', $config);
  $this->upload->initialize($config);

  if (!$this->upload->do_upload('attachment')) {
    $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
    return redirect('admin/product');
  }

  $uploadData = $this->upload->data();
  $path = $uploadData['full_path'];
  $selected_branch_id = $this->current_admin_branch_id();
  $user_id = (int)$this->session->userdata('user_id');
  $imported = 0;
  $skipped = 0;

  try {
    $Reader = new SpreadsheetReader($path);
    $sheets = $Reader->Sheets();

    $this->load->model('queries');
    $this->db->trans_start();

    foreach ($sheets as $sheet_index => $sheet_name) {
      $Reader->ChangeSheet($sheet_index);
      $row_number = 0;

      foreach ($Reader as $row) {
        $row_number++;
        if ($row_number === 1) {
          continue;
        }

        $name = trim((string)($row[0] ?? ''));
        if ($name === '') {
          $skipped++;
          continue;
        }

        if ($this->queries->check_productName($name)) {
          $skipped++;
          continue;
        }

        $category = trim((string)($row[1] ?? ''));
        $unit = trim((string)($row[2] ?? ''));
        $branch_id = $selected_branch_id ?: (int)($row[10] ?? 0);
        $quantity = (float)($row[4] ?? 0);
        $buy_price = (float)($row[5] ?? 0);
        $price = (float)($row[6] ?? 0);
        $ju_price = (float)($row[7] ?? 0);

        if (!in_array($category, $this->allowed_product_categories(), true) || $unit === '' || $branch_id <= 0 || $quantity <= 0 || $buy_price <= 0 || ($price <= 0 && $ju_price <= 0)) {
          $skipped++;
          continue;
        }

        $product = [
          'name' => $name,
          'category' => $category,
          'unit' => $unit,
          'bland' => trim((string)($row[3] ?? '')),
          'quantity' => $quantity,
          'buy_price' => $buy_price,
          'price' => $price,
          'ju_price' => $ju_price,
          'stock_limit' => (float)($row[8] ?? 0),
          'exp_date' => trim((string)($row[9] ?? '')),
          'branch_id' => $branch_id,
          'user_id' => $user_id,
        ];

        if ($this->db->field_exists('reason', 'product')) {
          $product['reason'] = 'purchased';
        }

        $product_id = $this->queries->insert_product($product);
        if ($product_id > 0) {
          $out_balance = 0;
          $total_buy = $buy_price * $quantity;
          $total_sell = $price * $quantity;
          $total_ju = $ju_price * $quantity;
          $this->insert_store($product_id, $quantity, $out_balance, $total_buy, $total_sell, $total_ju, $branch_id);
          $this->insert_product_main_store($product_id, $quantity, $total_buy, $total_sell, $total_ju, $out_balance);
          $this->record_stock_movement($product_id, $quantity, $user_id, 'PURCHASED');
          $imported++;
        } else {
          $skipped++;
        }
      }
    }

    $this->db->trans_complete();
  } catch (Exception $e) {
    @unlink($path);
    $this->session->set_flashdata('error', 'Import failed: '.$e->getMessage());
    return redirect('admin/product');
  }

  @unlink($path);

  if ($imported > 0) {
    $message = 'Imported '.$imported.' product(s).';
    if ($skipped > 0) {
      $message .= ' Skipped '.$skipped.' row(s).';
    }
    $this->session->set_flashdata('massage', $message);
  } else {
    $this->session->set_flashdata('error', 'No products imported. Check your file columns and required values.');
  }

  return redirect('admin/product');
  }


  public function service(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $service = $this->queries->get_service();
      // print_r($service);
      //    exit();
    $this->load->view('admin/service',['my'=>$my,'service'=>$service]);
  }


  public function create_service(){
    $this->form_validation->set_rules('huduma_name','Service','required');
    $this->form_validation->set_rules('huduma_price','Price','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        // print_r($data);
        //      exit();
        $this->load->model('queries');
        if ($this->queries->insert_service($data)) {
          $this->session->set_flashdata("massage",'Service Saved successfully');
        }else{
          $this->session->set_flashdata("error",'Failed');

        }
        return redirect('admin/service');
    }
    $this->service();
  }

  public function edit_service($huduma_id){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $huduma = $this->queries->get_edit_service($huduma_id);
    $this->load->view('admin/edit_service',['my'=>$my,'huduma'=>$huduma]);
  }


  public function modify_huduma($huduma_id){
      $this->form_validation->set_rules('huduma_name','Service','required');
    $this->form_validation->set_rules('huduma_price','Price','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        // print_r($data);
        //      exit();
        $this->load->model('queries');
        if ($this->queries->update_service($data,$huduma_id)) {
          $this->session->set_flashdata("massage",'Service Updated successfully');
        }else{
          $this->session->set_flashdata("error",'Failed');
        }
        return redirect('admin/edit_service/'.$huduma_id);
    }
    $this->edit_service();
  }


  public function delete_service($huduma_id){
    $this->load->model('queries');
    if($this->queries->remove_service($huduma_id));
    $this->session->set_flashdata('massage','Service Deleted successfully');
    return redirect('admin/service');
  }


  public function recod_service(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $customer = $this->queries->get_customer();
    $service = $this->queries->get_service();
    $recod = $this->queries->get_today_service();
    $sum_service = $this->queries->get_total_service_today();
     //  echo "<pre>";
     // print_r($sum_service);
     //      exit();
    $this->load->view('admin/recod_service',['my'=>$my,'customer'=>$customer,'service'=>$service,'recod'=>$recod,'sum_service'=>$sum_service]);
  }

  public function edit_recod($receive_id){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $customer = $this->queries->get_customer();
    $service = $this->queries->get_service();
    $receive_recod = $this->queries->get_edit_receve($receive_id);
      // echo "<pre>";
      // print_r($receive_recod);
      //        exit();
    $this->load->view('admin/edit_recod',['my'=>$my,'customer'=>$customer,'service'=>$service,'receive_recod'=>$receive_recod]);
  }

  public function create_recod_service(){
    $this->load->model('queries');
    $this->form_validation->set_rules('customer_id','Customer','required');
    $this->form_validation->set_rules('huduma_id','Service','required');
    $this->form_validation->set_rules('user_id','Seller','required');
    $this->form_validation->set_rules('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        $customer_id = $data['customer_id'];
        $user_id = $data['user_id'];
        $huduma_id = $data['huduma_id'];
       
        $service = $this->queries->get_edit_service($huduma_id);
        $price = $service->huduma_price;
        $customery = $this->queries->get_editCustomer($customer_id);
        $huduma = $this->queries->get_edit_service($huduma_id);
        $customer = $customery->customer_name;
        $total_price = $huduma->huduma_price;
          // print_r($customer);
          // echo "<br>";
          //  print_r($total_price);
          //     exit();
        
        if ($this->insert_recod_service($huduma_id,$user_id,$price,$customer_id)) {
           $this->session->set_flashdata('massage','Service Record saved successfully');
        }else{
             $this->session->set_flashdata('massage','Service Record saved successfully');
             $this->insert_cashire_report($customer,$total_price);
        }
        return redirect('admin/recod_service');
    }
    $this->recod_service();
  }

  public function modify_receive_recod($receive_id){
    $this->load->model('queries');
     $this->form_validation->set_rules('customer_id','Customer','required');
    $this->form_validation->set_rules('huduma_id','Service','required');
    $this->form_validation->set_rules('user_id','Seller','required');
    $this->form_validation->set_rules('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        $customer_id = $data['customer_id'];
        $user_id = $data['user_id'];
        $huduma_id = $data['huduma_id'];

        $service = $this->queries->get_edit_service($huduma_id);
        $price = $service->huduma_price;
          // print_r($price);
          //     exit();
        if ($this->update_recod_service($receive_id,$huduma_id,$price,$customer_id)) {
           $this->session->set_flashdata('massage','Service Record saved successfully');
        }else{
             $this->session->set_flashdata('massage','Service Record saved successfully');
        }
        return redirect('admin/recod_service');
    }
    $this->recod_service(); 
  }


   public function update_recod_service($receive_id,$huduma_id,$price,$customer_id){
  $sqldata="UPDATE `tbl_receve_huduma` SET `huduma_id`= '$huduma_id', `price` = '$price',`customer_id`='$customer_id' WHERE `receive_id`= '$receive_id'";
    // print_r($sqldata);
    //    exit();
  $query = $this->db->query($sqldata);
  return true;
}

public function delete_recod($receive_id){
  $this->load->model('queries');
  if($this->queries->remove_recod($receive_id));
  $this->session->set_flashdata('massage','Service Record Deleted successfully');
  return redirect("admin/recod_service");
}

// public function previous_recod(){
//   $this->load->model('queries');
//     $user_id = $this->session->userdata('user_id');
//     $my = $this->queries->get_mydata($user_id);
//   $this->load->view('admin/previous_recod',['my'=>$my]);
// }

public function prev_recod(){
  $this->load->model('queries');
  $from = $this->input->post('from');
  $to = $this->input->post('to');
  $data = $this->queries->get_prev_recod($from,$to);
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $sum_data = $this->queries->get_sum_prev_recod($from,$to);
    //  echo "<pre>";
    // print_r($sum_data);
    //      exit();
  $this->load->view('admin/previous_recod',['data'=>$data,'my'=>$my,'sum_data'=>$sum_data]);
}


   public function insert_recod_service($huduma_id,$user_id,$price,$customer_id){
    $date = date("Y-m-d");
      $this->db->query("INSERT INTO tbl_receve_huduma (`huduma_id`,`user_id`,`price`,`customer_id`,`date`) VALUES ('$huduma_id','$user_id','$price','$customer_id','$date')");
      }

  public function register_customer(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $customer = $this->queries->get_customer();
     // print_r($customer);
     //       exit();
    $this->load->view('admin/customer',['my'=>$my,'customer'=>$customer]);
  }


  public function create_customer(){
    $this->form_validation->set_rules('customer_name','Name','required');
    $this->form_validation->set_rules('customer_no','Phone number','required');
    $this->form_validation->set_rules('date','Date','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
         $data = $this->input->post();
         // print_r($data);
         //     exit();
         $this->load->model('queries');
         if ($this->queries->insert_customer($data)) {
            $this->session->set_flashdata('massage','Cutomer Registration successfully');
         }else{
        $this->session->set_flashdata('error','Failed');

         }
         return redirect('admin/register_customer');
    }
    $this->register_customer();
  }

  public function edit_customer($customer_id){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $customer = $this->queries->get_editCustomer($customer_id);
      // print_r($customer);
      //      exit();
    $this->load->view('admin/edit_customer',['my'=>$my,'customer'=>$customer]);
  }


  public function modify_customer($customer_id){
    $this->form_validation->set_rules('customer_name','Name','required');
    $this->form_validation->set_rules('customer_no','Phone number','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
         $data = $this->input->post();
         // print_r($data);
         //     exit();
         $this->load->model('queries');
         if ($this->queries->update_customer($data,$customer_id)) {
            $this->session->set_flashdata('massage','Cutomer Updated successfully');
         }else{
        $this->session->set_flashdata('error','Failed');

         }
         return redirect('admin/edit_customer/'.$customer_id);
    }
    $this->edit_customer();
  }

  public function delete_customer($customer_id){
    $this->load->model('queries');
    if($this->queries->remove_customer($customer_id));
    $this->session->set_flashdata('massage','Customer Deleted successfully');
    return redirect('admin/register_customer');
  }

  public function monthly_report(){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $from = $this->input->post('from');
  $to = $this->input->post('to');
  $sales_data = $this->queries->get_monthly_report($from,$to);
  $sum_all_sales = $this->queries->get_allSales($from,$to);
  $retail_sale = $this->queries->get_allSalesRetail($from,$to);
  $whole = $this->queries->get_allSalesWhole($from,$to);
  $profit_all = $this->queries->get_alLprofit($from,$to);
  $retail_profit = $this->queries->get_alLprofitRetail($from,$to);
  $whole_profit = $this->queries->get_alLWHOLEPROFIT($from,$to);
  $expenses = $this->queries->get_ExpensesTotal($from,$to);
  //   echo "<pre>";
  // print_r($expenses);
  //         exit();
  $this->load->view('admin/monthly_report',['my'=>$my,'sales_data'=>$sales_data,'from'=>$from,'to'=>$to,'sum_all_sales'=>$sum_all_sales,'retail_sale'=>$retail_sale,'whole'=>$whole,'profit_all'=>$profit_all,'retail_profit'=>$retail_profit,'whole_profit'=>$whole_profit,'expenses'=>$expenses]);
  }



  public function get_total_sell(){
    //$date = date("Y");
    $data = $this->db->query("SELECT YEAR(sell_day) AS years,SUM(total_sell_price) AS total_sells,SUM(profit) AS total_prof FROM tbl_sell GROUP BY YEAR(sell_day)");
    echo json_encode($data->result());
  }


  public function get_expenses(){
    $exp = $this->db->query("SELECT YEAR(day) AS year_list,SUM(price) AS total_price FROM cash_flow GROUP BY YEAR(day)");
    echo json_encode($exp->result());
  }

  public function get_paylor(){
    $pay = $this->db->query("SELECT YEAR(date) AS pay_year,SUM(pay_amount) AS total_payrol FROM tbl_mishahara GROUP BY YEAR(date)");
    echo json_encode($pay->result());
  }





 public function product_add_Store(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $selected_branch_id = $this->current_admin_branch_id();
      $branches = $this->queries->get_branches();
      $product = $this->queries->get_store_product_available($selected_branch_id);
      $my = $this->queries->get_mydata($user_id);
      $privillage = $this->queries->get_userPrivillage($user_id);
       //  echo "<pre>";
       // print_r($product);
       //         exit();
      $this->load->view('admin/add_form',['product'=>$product,'my'=>$my,'privillage'=>$privillage,'branches'=>$branches,'selected_branch_id'=>$selected_branch_id]);
    }

      public function adjust_product_stoo(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $product = $this->queries->get_store_product_available();
      $my = $this->queries->get_mydata($user_id);
      $privillage = $this->queries->get_userPrivillage($user_id);
      $this->load->view('admin/adjust',['product'=>$product,'my'=>$my,'privillage'=>$privillage]);
    }


    
  public function privillage($user_id){
    $this->load->model('queries');
   $my = $this->queries->get_mydata($user_id);
   $cutomer = $this->queries->view_user($user_id);
   $priv = $this->queries->get_privillage($user_id);
   $user_access = array();
   foreach ($priv as $privillage) {
    $user_access[] = $privillage->privillage;
   }
    $this->load->view('admin/user_privillage',['my'=>$my,'user_id'=>$user_id,'cutomer'=>$cutomer,'priv'=>$priv,'user_access'=>$user_access]);
  }

  public function create_privillage($user_id){
          $this->load->model('queries');
          $posted_user_id = (int)$this->input->post('user_id');
          $user_id = $posted_user_id ? $posted_user_id : (int)$user_id;
          $user = $this->queries->view_user($user_id);

          if (!$user || $user->role !== 'seller') {
          $this->queries->replace_user_privillages($user_id, array());
          $this->session->set_flashdata('error','Access privileges are only for seller users.');
          return redirect("admin/privillage/".$user_id);
          }

          $privillage = array_values(array_intersect((array)$this->input->post('privillage'), array('seller', 'product', 'store')));

          $this->queries->replace_user_privillages($user_id, $privillage);
          $this->session->set_flashdata('massage','User access updated successfully');

          return redirect("admin/privillage/".$user_id); 
       }

       public function remove_privillage($id){
        $this->load->model('queries');
        $privillage = $this->queries->get_privillageData($id);
        $user_id = $privillage->user_id;
        if($this->queries->delete_privillage($id));
        $this->session->set_flashdata('massage','Removed successfully');
        return redirect('admin/privillage/'.$user_id);
       }

        public function print_recept($text){
    $this->load->model('queries');
    $cartItems = $this->cart->contents();
    $shop = $this->queries->get_shop_infoData();
     //   echo "<pre>";
     // print_r( $text);
     // echo "</pre>";
     //     exit();
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('admin/recept',['shop'=>$shop,'cartItems'=>$cartItems,'text'=>$text],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
         
    }


 

     public function print_receptJumla($text){
    $this->load->model('queries');
    $cartItems = $this->cart->contents();
    $shop = $this->queries->get_shop_infoData();
     //   echo "<pre>";
     // print_r( $item_recept);
     // echo "</pre>";
     //     exit();
    $mpdf = new \Mpdf\Mpdf();
    $html = $this->load->view('seller/recept_jumla',['shop'=>$shop,'cartItems'=>$cartItems,'text'=>$text],true);
    $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
         
    }


    public function supplier(){
      $this->load->model('queries');
      $user_id = $this->session->userdata('user_id');
      $my = $this->queries->get_mydata($user_id);
      $supplier = $this->queries->get_supplier();
      // print_r($supplier);
      //           exit();
      $this->load->view('admin/supplier',['my'=>$my,'supplier'=>$supplier]);
    }


  public function create_supplier(){
    $this->form_validation->set_rules('supplier_name','Name','required');
    $this->form_validation->set_rules('supplier_phone','Name','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        // echo "<pre>";
        // print_r($data);
        //      exit();
        $this->load->model('queries');
        if ($this->queries->insert_supplier($data)) {
            $this->session->set_flashdata('massage','Data saved successfully');
        }else{
          $this->session->set_flashdata('error','Failed');
        }
        return redirect("admin/supplier");
    }
    $this->supplier();
  }


public function delete_supplier($id){
  $this->load->model('queries');
  if($this->queries->remove_supplier($id));
  $this->session->set_flashdata('massage','Data Deleted successfully');
  return redirect('admin/supplier');
}


public function edit_supplier($id){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $data_supplier = $this->queries->get_edit_supplier($id);
  $my = $this->queries->get_mydata($user_id);
  $this->load->view('admin/edit_supplier',['data_supplier'=>$data_supplier,'my'=>$my]);
}


 public function modify_supplier($id){
    $this->form_validation->set_rules('supplier_name','Name','required');
    $this->form_validation->set_rules('supplier_phone','Name','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
        $data = $this->input->post();
        // echo "<pre>";
        // print_r($data);
        //      exit();
        $this->load->model('queries');
        if ($this->queries->modify_supplier($id,$data)) {
            $this->session->set_flashdata('massage','Data updated successfully');
        }else{
          $this->session->set_flashdata('error','Failed');
        }
        return redirect("admin/supplier");
    }
    $this->supplier();
  }

  public function place_order(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $purchase = $this->queries->get_all_product_purchase();
    $supplier = $this->queries->get_supplier();
    //     echo "<pre>";
    // print_r($supplier);
    //         exit();
    
    $this->load->view('admin/place_order',['my'=>$my,'purchase'=>$purchase,'supplier'=>$supplier]);
  }


  public function create_order(){
    $this->load->helper('string');
    $validation  = array( array('field'=> 'product_id[]','rules'=>'required'));
          $this->form_validation->set_rules($validation);
           if ($this->form_validation->run() == true) {
               $product_id  = $this->input->post('product_id[]');
               $supplier_id = $this->input->post('supplier_id');
               $pack_size = $this->input->post('pack_size[]');
               $quantity_pack = $this->input->post('quantity_pack[]');
               $buy_price_pack = $this->input->post('buy_price_pack[]');
               $total_amount = $this->input->post('total_amount[]');
               $oreder_id = random_string('numeric',4);

              // print_r($product_id);
              //     echo "<br>";
              // print_r($pack_size);
              //     echo "<br>";
              // //print_r($quantity_pack);
              //    echo "<br>";
              // //print_r($buy_price_pack);
              //    echo "<br>";
              // //print_r($total_amount);
              //     exit();
            for($i=0; $i<count($product_id);$i++){
      $date = date("Y-m-d");
      $this->db->query("INSERT INTO  tbl_purchase_record(`product_id`,`supplier_id`,`pack_size`,`quantity_pack`,`buy_price_pack`,`total_amount`,`order_id`,`date`) VALUES ('".$product_id[$i]."','$supplier_id','".$pack_size[$i]."','".$quantity_pack[$i]."','".$buy_price_pack[$i]."','".$total_amount[$i]."','$oreder_id','$date')");

         // print_r($order);
         //          exit();
           }
        $order_total = $this-> get_order_data($oreder_id);
        $order = $order_total->total_price_product;
        $this->insert_order_purches($oreder_id,$order);

          $this->session->set_flashdata('massage','Order Placed successfully');
       
           }
        return redirect("admin/placeorder_next/".$oreder_id); 
  }


  public function placeorder_next($oreder_id){
    $order_id = $oreder_id;
    // print_r($order_id);
    //      exit();
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $purchase = $this->queries->get_all_product_purchase();
    $order_data = $this->queries->get_first_order($order_id);
    $supplier_detail = $this->queries->get_supplier_detail_order($order_id);
    // print_r($order_data);
    //          exit();
    $this->load->view('admin/order_list_product',['my'=>$my,'purchase'=>$purchase,'supplier_detail'=>$supplier_detail]);
  }


  public function create_another_order($order_id){
    $this->form_validation->set_rules('product_id','product','required');
    $this->form_validation->set_rules('supplier_id','Supplier','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
    if ($this->form_validation->run()) {
       $data = $this->input->post();

       // print_r($data);
       //   exit();
       $this->load->model('queries');
       if ($this->queries->insert_another_product_order($data)) {
           $this->session->set_flashdata('massage','Order Placed successfully');
       }
       return redirect('admin/placeorder_next/'.$order_id);
    }
    $this->placeorder_next();
  }


  public function get_order_data($oreder_id){
    $data = $this->db->query("SELECT SUM(total_amount) AS total_price_product FROM tbl_purchase_record WHERE order_id = '$oreder_id'");
    return $data->row();
  }


  public function insert_order_purches($order_id,$order){

  $this->db->query("INSERT INTO tbl_purches_order (`order_id`,`total_order`) VALUES ('$order_id','$order')");
  }

  public function order_record(){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $order_history = $this->queries->get_order_history();
    //   echo "<pre>";
    // print_r($order_history);
    //           exit();
    $this->load->view('admin/order_record',['my'=>$my,'order_history'=>$order_history]);
  }


  public function view_supplier_order($order_id){
    $this->load->model('queries');
    $user_id = $this->session->userdata('user_id');
    $my = $this->queries->get_mydata($user_id);
    $order_list = $this->queries->get_order_supplier($order_id);
    $my = $this->queries->get_mydata($user_id);
    $supplier_detail = $this->queries->get_supplier_detail_order($order_id);
    $total_order_amount = $this->queries->get_total_order($order_id);
    $payment_history = $this->queries->get_cash_transaction($order_id);
    //  echo "<pre>";
    // print_r($payment_history);
    //          exit();
    $this->load->view('admin/supplier_order',['my'=>$my,'order_list'=>$order_list,'supplier_detail'=>$supplier_detail,'total_order_amount'=>$total_order_amount,'payment_history'=>$payment_history]);
  }

public function print_daily_purchase($order_id){
  $this->load->model('queries');
  $order_list = $this->queries->get_order_supplier($order_id);
  $supplier_detail = $this->queries->get_supplier_detail_order($order_id);
  $total_order_amount = $this->queries->get_total_order($order_id);
  $shop = $this->queries->get_shop_infoData();

  $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4-L','orientation' => 'L']);
  $html = $this->load->view('admin/daily_purchase_report',['order_list'=>$order_list,'supplier_detail'=>$supplier_detail,'total_order_amount'=>$total_order_amount,'shop'=>$shop],true);
  $mpdf->SetFooter('Generated By (0) 629364847 & (0) 748470181');
  $mpdf->WriteHTML($html);
  $mpdf->Output();
  //$this->load->view('admin/daily_purchase_report');
 }

 public function add_order_product($order_id)
 {
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $purchase = $this->queries->get_all_product_purchase();
  $total_order_amount = $this->queries->get_total_order($order_id);
  $my = $this->queries->get_mydata($user_id);
  $supplier_detail = $this->queries->get_supplier_detail_order($order_id);
  $this->load->view('admin/add_order',['purchase'=>$purchase,'total_order_amount'=>$total_order_amount,'my'=>$my,'supplier_detail'=>$supplier_detail]);
 }


 public function create_add_order($order_id)
 {
  $this->form_validation->set_rules('product_id','product','required');
  $this->form_validation->set_rules('order_id','Order','required');
  $this->form_validation->set_rules('date','date','required');
  $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

  if ($this->form_validation->run()) {
     $data = $this->input->post();
     // print_r($data);
     //     exit();
     $this->load->model('queries');
     if ($this->queries->insert_order_adition($data)) {
        $this->session->set_flashdata("massage",'Product Added successfully');
     }else{
        $this->session->set_flashdata("error",'Failed');
    }
    return redirect('admin/view_supplier_order/'.$order_id);
  }
  $this->view_supplier_order();
 }






  public function create_order_payment($order_id){
    $this->load->model('queries');
    $this->form_validation->set_rules('user_id','user','required');
    $this->form_validation->set_rules('pay_amount','Payment Amount','required');
    $this->form_validation->set_rules('pay_by','Payment Method','required');
    $this->form_validation->set_rules('order_id','user','required');
    $this->form_validation->set_rules('pay_date','user','required');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

    if ($this->form_validation->run()) {
        $data = $this->input->post();
        $pay_amount = $data['pay_amount'];

        $purchase_amount = $this->queries->get_paid_amount($order_id);
        $paid_amount = $purchase_amount->paid_amount;

        $old_paid = $paid_amount + $pay_amount;
        //       echo "<pre>";
        // print_r($old_paid);
        //           exit();
        
        if ($this->queries->insert_payment_record($data)) {
          $this->update_payment_amount($order_id,$old_paid);
          $this->session->set_flashdata("massage","Payment record successfully");
        }else{
          $this->session->set_flashdata("error","Failed"); 
        }
        return redirect('admin/view_supplier_order/'.$order_id);
    }
    $this->view_supplier_order();
  }


  public function update_payment_amount($order_id,$old_paid){
    $sqldata="UPDATE `tbl_purches_order` SET `paid_amount`= '$old_paid' WHERE `order_id`= '$order_id'";
    $query = $this->db->query($sqldata);
    return true; 
  }


  public function edit_puechase($req_id,$order_id){
  $this->load->model('queries');
  $user_id = $this->session->userdata('user_id');
  $my = $this->queries->get_mydata($user_id);
  $supplier_detail = $this->queries->get_supplier_detail_order($order_id);
  $request = $this->queries->get_total_purchase_data($req_id);
  //    echo "<pre>";
  // print_r($request);
  //      exit();
  $this->load->view('admin/edit_parchase',['my'=>$my,'supplier_detail'=>$supplier_detail,'request'=>$request]);
 }


  public function modify_order($req_id){
     $this->load->model('queries');
    $this->form_validation->set_rules('pack_size','Pack size','required');
    $this->form_validation->set_rules('quantity_pack','quantity pack','required');
    $this->form_validation->set_rules('buy_price_pack','buy price','required');
    $this->form_validation->set_rules('total','Total');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');

    if ($this->form_validation->run()) {
       $data = $this->input->post();
         //echo "<pre>";
       // print_r($data);
       //      exit();
       $pack_size = $data['pack_size'];
       $quantity_pack = $data['quantity_pack'];
       $buy_price_pack = $data['buy_price_pack'];
       $balance = $data['total'];
       

       $total_amount = $quantity_pack * $buy_price_pack;
       // print_r($total_amount);
       //            exit();
      
       $request = $this->queries->get_supplier_order_id($req_id);
       $oreder_id = $request->order_id;
       $product_id = $request->product_id;

        $product = $this->queries->get_product_safe($product_id);
        $buy_price = $product->buy_price;
        $sell_price = $product->price;
        $product_name = $product->name;
        $ju_sellprice = $product->ju_price;
        $main_stoo = $this->queries->get_mainTransforbalance($product_id);
        $remain_balance = $main_stoo->balance;

        $moved_product = 0;
        $new_balance = $remain_balance + $balance;
        //     echo "<pre>";
        // print_r($buy_price);
        //            exit();
        $total_buy = $new_balance * $buy_price;
        $total_sell = $new_balance * $sell_price;
        $total_jusell = $new_balance * $ju_sellprice;

       // print_r($total_buy);
       //          exit();
       if ($this->update_order_request($req_id,$pack_size,$quantity_pack,$buy_price_pack,$total_amount,$balance)) {
           $this->get_order_data($oreder_id);
           $order_total = $this->get_order_data($oreder_id);
           $order = $order_total->total_price_product;
           $this->update_purchse_order($oreder_id,$order);
           $this->update_main_stooProduct($product_id,$new_balance,$total_buy,$total_sell,$moved_product,$total_jusell);
           $this->session->set_flashdata('massage','Data updated successfully');
       }else{
        $this->session->set_flashdata('error','Failed');
       }

       return redirect('admin/view_supplier_order/'.$oreder_id);
    }
  }


 public function update_order_request($req_id,$pack_size,$quantity_pack,$buy_price_pack,$total_amount,$balance){
  $date = date("Y-m-d");
  $sqldata="UPDATE `tbl_purchase_record` SET `pack_size`= '$pack_size',`quantity_pack`='$quantity_pack',`buy_price_pack`='$buy_price_pack',`total_amount`='$total_amount',`date`='$date',`total`='$balance' WHERE `id`= '$req_id'";
    $query = $this->db->query($sqldata);
    return true; 
 }


 public function update_purchse_order($oreder_id,$order){
  $sqldata="UPDATE `tbl_purches_order` SET `total_order`= '$order' WHERE `order_id`= '$oreder_id'";
    $query = $this->db->query($sqldata);
    return true; 
 }


public function delete_request_order($req_id){
  $this->load->model('queries');
  $request = $this->queries->get_supplier_order_id($req_id);
  $order_id = $request->order_id;
  $req_amount = $request->total_amount;
  $total = $request->total;
  $product_id = $request->product_id;


  //update product remain function

        $product = $this->queries->get_product_safe($product_id);
        $buy_price = $product->buy_price;
        $sell_price = $product->price;
        $product_name = $product->name;
        $ju_sellprice = $product->ju_price;
        $main_stoo = $this->queries->get_mainTransforbalance($product_id);
        $remain_balance = $main_stoo->balance;

        $moved_product = 0;
        $new_balance = $remain_balance - $total;
        //     echo "<pre>";
        // print_r($buy_price);
        //         exit();
        $total_buy = $new_balance * $buy_price;
        $total_sell = $new_balance * $sell_price;
        $total_jusell = $new_balance * $ju_sellprice;

      // print_r($product_id);
      // echo "<br>";
      //  print_r($new_balance);
      //        exit();
       $total_amount = $this->queries->get_total_order($order_id);
       $total_order = $total_amount->total_order;

       $remain_order = $total_order - $req_amount;
       $order = $remain_order;
       $oreder_id = $order_id;
       $this->update_purchse_order($oreder_id,$order);
       $this->update_main_stooProduct($product_id,$new_balance,$total_buy,$total_sell,$moved_product,$total_jusell);
     
   // print_r($remain_order);
   //          exit();
  if($this->queries->remove_request_order($req_id));
  $this->session->set_flashdata('massage','Data Deleted successfully');
  return redirect('admin/view_supplier_order/'.$oreder_id);
}


public function delete_purchase_history($pay_id){
  $this->load->model('queries');
  $order_list = $this->queries->get_purchase_order($pay_id);
  $order_id = $order_list->order_id;
  $pay_amount = $order_list->pay_amount;
   
  $total_amount = $this->queries->get_total_order($order_id);
  $paid_amount = $total_amount->paid_amount;

  $remain_paid = $paid_amount - $pay_amount;
  $this->update_purchse_paid($order_id,$remain_paid);
    // print_r($remain_paid);
    //          exit();
  if($this->queries->remove_purches_list($pay_id));
  $this->session->set_flashdata('massage','Data Deleted successfully');
  return redirect('admin/view_supplier_order/'.$order_id);
}


 public function update_purchse_paid($order_id,$remain_paid){
  $sqldata="UPDATE `tbl_purches_order` SET `paid_amount`= '$remain_paid' WHERE `order_id`= '$order_id'";
  $query = $this->db->query($sqldata);
  return true; 
 }


 






		//session destroy
	  public function __construct(){
		parent::__construct();
		if (!$this->session->userdata("user_id"))
      //session_destroy();
			return redirect("home/index");
}	

}
