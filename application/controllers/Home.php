<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    private function get_passport_upload_route($role)
    {
        if ($role == 'admin') {
            return 'admin/profile_pc';
        }

        if ($role == 'seller') {
            return 'seller/passport_required';
        }

        if ($role == 'cashier') {
            return 'cashire/profile_pc';
        }

        return 'home/index';
    }

	public function index()
	{
        $this->load->model('queries');
        $shop = $this->queries->get_shop_infoData();
		$this->load->view('home/home',['shop'=>$shop]);
	}

    public function create_account()
    {
        $this->load->model('queries');
        $this->queries->ensure_branch_table();
        $this->load->view('home/create_account');
    }

    public function register_account()
    {
        $this->form_validation->set_rules('shop_name', 'shop name', 'required|trim');
        $this->form_validation->set_rules('location', 'shop location', 'required|trim');
        $this->form_validation->set_rules('shop_phone', 'shop phone', 'required|trim');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email');
        $this->form_validation->set_rules('branch_name', 'branch name', 'required|trim');
        $this->form_validation->set_rules('admin_name', 'admin name', 'required|trim');
        $this->form_validation->set_rules('phone_number', 'admin phone number', 'required|trim');
        $this->form_validation->set_rules('password', 'password', 'required|min_length[4]');
        $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');
        $this->form_validation->set_error_delimiters('<p class="text-xs text-red-600 mt-2">', '</p>');

        if (!$this->form_validation->run()) {
            return $this->create_account();
        }

        $this->load->model('queries');

        if ($this->queries->phone_number_exists($this->input->post('phone_number', true))) {
            $this->session->set_flashdata('ms', 'Phone number already registered');
            return redirect('home/create_account');
        }

        $shopData = [
            'shop_name' => $this->input->post('shop_name', true),
            'po_box' => $this->input->post('po_box', true),
            'location' => $this->input->post('location', true),
            'phone' => $this->input->post('shop_phone', true),
            'email' => $this->input->post('email', true),
        ];

        $branchData = [
            'branch_name' => $this->input->post('branch_name', true),
            'location' => $this->input->post('location', true),
            'phone' => $this->input->post('shop_phone', true),
            'email' => $this->input->post('email', true),
            'is_main' => 1,
            'status' => 'open',
        ];

        $adminData = [
            'full_name' => $this->input->post('admin_name', true),
            'phone_number' => $this->input->post('phone_number', true),
            'img' => '',
            'role' => 'admin',
            'status' => 'open',
            'password' => sha1($this->input->post('password')),
            'joining' => date('Y-m-d'),
        ];

        $created = $this->queries->create_initial_account($shopData, $branchData, $adminData);

        if (!$created) {
            $this->session->set_flashdata('ms', 'Failed to create account. Please try again.');
            return redirect('home/create_account');
        }

        $this->session->set_userdata([
            'user_id' => $created['user_id'],
            'phone_number' => $adminData['phone_number'],
            'full_name' => $adminData['full_name'],
            'role' => 'admin',
            'branch_id' => $created['branch_id'],
        ]);

        $this->session->set_flashdata('massage', 'Account created successfully. Please upload passport first.');
        return redirect('admin/profile_pc');
    }
     
 public function signin(){
$this->form_validation->set_rules('phone_number','phone number','required');
$this->form_validation->set_rules('password','password','required');
$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
if ($this->form_validation->run() ) {
$phone_number = $this->input->post('phone_number');
$password = sha1($this->input->post('password'));
$this->load->model('queries');
$userexit = $this->queries->user_data($phone_number,$password);
// print_r($userexit);
//    exit();
if ($userexit ) {
if ($userexit->role == 'admin') {
    $sessionData = [
    'user_id' => $userexit->user_id,
    'phone_number' => $userexit->phone_number,
    'full_name' => $userexit->full_name,
    'role' => $userexit->role,
    'branch_id' => $userexit->branch_id,
    ];

   //  print_r($userexit);
   // exit();
    if ($userexit->status == 'open') {
$this->session->set_userdata($sessionData);
$passportRoute = $this->get_passport_upload_route($userexit->role);
if (empty(trim($userexit->img ?? ''))) {
    $this->session->set_flashdata('ms','Please upload passport first');
    return redirect($passportRoute);
}
$this->session->set_flashdata('massage','Login Successfully');
return redirect('admin/index');
}elseif ($userexit->status == 'close') {
$this->session->set_userdata($sessionData);
$this->session->set_flashdata('ms','Account closed');
    return redirect("home/index");
}

}elseif($userexit->role == 'seller') {
    $sessionData = [
    'user_id' => $userexit->user_id,
    'phone_number' => $userexit->phone_number,
    'full_name' => $userexit->full_name,
    'role' => $userexit->role,
    'branch_id' => $userexit->branch_id,

    ];
    //     exit();
    // print_r($sessionData);
if ($userexit->status =='open') {
$this->session->set_userdata($sessionData);
$passportRoute = $this->get_passport_upload_route($userexit->role);
if (empty(trim($userexit->img ?? ''))) {
    $this->session->set_flashdata('ms','Please upload passport first');
    return redirect($passportRoute);
}
$this->session->set_flashdata('massage','Login Successfully');
          return redirect("seller/index");
    }elseif($userexit->status == 'close'){
$this->session->set_flashdata('massage','account closed');
    return redirect("home/index");

  }
}elseif($userexit->role == 'cashier') {
    $sessionData = [
    'user_id' => $userexit->user_id,
    'phone_number' => $userexit->phone_number,
    'full_name' => $userexit->full_name,
    'role' => $userexit->role,
    'branch_id' => $userexit->branch_id,

    ];
    
    // print_r($sessionData);
    //     exit();
if ($userexit->status =='open') {
$this->session->set_userdata($sessionData);
$passportRoute = $this->get_passport_upload_route($userexit->role);
if (empty(trim($userexit->img ?? ''))) {
    $this->session->set_flashdata('ms','Please upload passport first');
    return redirect($passportRoute);
}
$this->session->set_flashdata('massage','Login Successfully');
          return redirect("cashire/dashboard");
    }elseif($userexit->status == 'close'){
$this->session->set_flashdata('massage','account closed');
    return redirect("home/index");

  }
}

}
else{

$this->session->set_flashdata('ms','Invalid your Phone number or password');
return redirect("home/index");
}
}
else{
$this->index();
}

}


//use log out
public function logout(){
$this->session->unset_userdata("user_id");
$this->session->unset_userdata("branch_id");
$this->session->set_flashdata('massage','Logout Successfully');
return  redirect("home/index");
}
}
