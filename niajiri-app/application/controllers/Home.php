<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public $data;

    public function __construct() {


        parent::__construct();
        error_reporting(0);
        $this->data['theme'] = 'user';
        $this->data['module'] = 'home';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model', 'home');

        $this->user_latitude = (!empty($this->session->userdata('user_latitude'))) ? $this->session->userdata('user_latitude') : '';
        $this->user_longitude = (!empty($this->session->userdata('user_longitude'))) ? $this->session->userdata('user_longitude') : '';

        $this->currency = settings('currency');

        $this->load->library('ajax_pagination');
        $this->perPage = 12;
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->helper('form');


        $default_language_select = default_language();

        if ($this->session->userdata('user_select_language') == '') {
            $this->data['user_selected'] = $default_language_select['language_value'];
        } else {
            $this->data['user_selected'] = $this->session->userdata('user_select_language');
        }

        $this->data['active_language'] = $active_lang = active_language();

        $lg = custom_language($this->data['user_selected']);

        $this->data['default_language'] = $lg['default_lang'];

        $this->data['user_language'] = $lg['user_lang'];

        $this->user_selected = (!empty($this->data['user_selected'])) ? $this->data['user_selected'] : 'en';

        $this->default_language = (!empty($this->data['default_language'])) ? $this->data['default_language'] : '';

        $this->user_language = (!empty($this->data['user_language'])) ? $this->data['user_language'] : '';
		
		/* Delete Services, Which is created for Users only not for Shop, after 30 days */
		$provider_id = $this->session->userdata('id');
		$usrserv = $this->db->query("SELECT id FROM services WHERE datediff(now(), created_at) > 30 AND status = 3")->result_array();
		if(count($usrserv) > 0) {
			foreach($usrserv as $usrser){
				$inp['status'] = 0;
				$updateService = $this->db->where('id',$usrser['id'])->update('services', $inp);	
			}
		}
		
		/*Delete Offers if Offer Ends*/
		$new_details['df'] = 1;
		$new_details['status'] = 1;
		$this->db->where('end_date < CURDATE()');
		$this->db->update('service_offers', $new_details);
		
		
		/*Change Coupon Status if Coupon Meet End Date*/		
		$coupon_input['status'] = 3;
		$this->db->where('end_date < CURDATE()');
		$this->db->update('service_coupons', $coupon_input);

    }

    public function index() {
        $this->data['page'] = 'index';
        $this->data['category'] = $this->home->get_category();

        $this->data['services'] = $this->home->get_service();
		
		$this->data['shops'] = $this->home->get_shops();
		
		$this->data['featured_shops'] = $this->home->get_featured_shops();
		
		$this->data['nearest_shops'] = $this->home->get_nearest_shops();
        //echo 'sss<pre>'; print_r($this->data['nearest_shops']); exit;
        //services with offer
        $this->data['offers'] = $this->home->get_offered_services();
        //get sub categories
        $this->data['subcats'] = $this->home->get_all_subcategories();
        $this->data['categories'] = $this->home->get_all_categories();
        $lang_id = $this->db->get_where('language', array('language_value'=>$this->user_selected))->row()->id;
        $this->data['blogs'] = $this->home->get_all_blogs($lang_id,3);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function escrow_test_api(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.escrow-sandbox.com/2017-09-01/transaction',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => 'nandakumar.somasundarm@dreamguystech.com:Dreams@123',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode(
                array(
                    'parties' => array(
                        array(
                          'role' => 'buyer',
                          'customer' => 'me',
                        ),
                        array(
                            'role' => 'seller',
                            'customer' => 'mari141996@yopmail.com',
                        ),
                    ),
                    'currency' => 'usd',
                    'description' => '1962 Fender Stratocaster',
                    'items' => array(
                        array(
                            'title' => '1962 Fender Stratocaster',
                            'description' => 'Like new condition, includes original hard case.',
                            'type' => 'general_merchandise',
                            'inspection_period' => 259200,
                            'quantity' => 1,
                            'schedule' => array(
                                array(
                                    'amount' => 95000.0,
                                    'payer_customer' => 'me',
                                    'beneficiary_customer' => 'mari141996@yopmail.com',
                                )
                            )
                        )
                    )
                )
            )
        )); 
        
        $output = curl_exec($curl);
        echo $output;
        curl_close($curl);

    }

    public function escrow_transaction_api(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.escrow-sandbox.com/2017-09-01/transaction',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => 'maripriya.mathiyalagan@dreamguystech.com:Mari@1996',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode(
                array(
                    'currency' => 'usd',
                    'items' => array(
                        array(
                            'description' => 'johnwick.com',
                            'schedule' => array(
                                array(
                                    'payer_customer' => 'me',
                                    'amount' => '1000.0',
                                    'beneficiary_customer' => 'mari141996@yopmail.com',
                                ),
                            ),
                            'title' => 'johnwick.com',
                            'inspection_period' => '259200',
                            'type' => 'domain_name',
                            'quantity' => '1',
                            
                        ),
                    ),
                    'description' => 'Test',
                    'parties' => array(
                        array(
                            'customer' => 'me',
                            'role' => 'buyer',
                        ),
                        array(
                            'customer' => 'mari141996@yopmail.com',
                            'role' => 'seller',
                        ),
                    ),
                )
            )
        ));

        $output = curl_exec($curl);
        echo $output;
        curl_close($curl);

    }

    public function mpesa_test_api()
    {
        $Passkey = '';
        $Amount= 1;
        $BusinessShortCode = '600988';
        $PartyA ="254708374149";
        $AccountReference ="CompanyXLTD";
        $TransactionDesc = 'Payment of X';
        $Timestamp =date('YmdHis');
        $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
        $headers=['Content-Type:application/json; charset=utf8'];
        $initiate_url='https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $callBackURL ='https://xxxxxxxxxxxxxxxxx.ngrok.io/processL/transact.php';

        function accessToken() {
            $ConsumerKey = 'PXvEFt6T2GOV2bIs6yOGuapcEf7dqZ8y';
            $ConsumerSecret = 'u0jSeNSIbBFS1IYN';
            $credentials = base64_encode($ConsumerKey.":".$ConsumerSecret);
            $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
            $curl = curl_init();
           curl_setopt($curl, CURLOPT_URL, $url);
           curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials,"Content-Type:application/json"));
           curl_setopt($curl, CURLOPT_HEADER, false);
           // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           $curl_response = curl_exec($curl);
           $access_token=json_decode($curl_response);
           curl_close($curl);
           
           return $access_token->access_token;

        }

        echo accessToken();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $initiate_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.accessToken()));
          $curl_post_data = array(
            'BusinessShortCode' =>$BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PartyA,
            'CallBackURL' => $callBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        // print_r($curl_response."<br>");

    }

    public function return_call_back()
    {
        // echo "Return";
        
            $callbackJSONData=file_get_contents('php://input');
            $callbackData=json_decode($callbackJSONData);
            $return_response=$callbackData;
            // $return_response=$_POST;
            $jsonobj = $return_response;
            $this->db->insert('callback_return_response', array('response' => $jsonobj));
        
    }

    public function confirmation_return()
    {
        echo "Confirmation";
    }

    public function validation_return()
    {
        echo "Validation";
    }
    
    
    public function test_c2b()
            {
                $post=$this->input->post();
                // msisdn - See safaricom daraja documentation and check your credentials for the specific number given for testing.

                // ref    - THE PAYBILL ACCOUNT NO

                // var_dump($this->mpesa->C2B("5000","254708374149","174379"));

                //C2B Simulate
                // $c2b_samplecode=$this->mpesa->C2B("5000","254708374149","174379");  // client

                 // Lipa na mpesa Simulate (Mpesa Express)
                // 254769854958
                $mpesa_simulate=$this->mpesa->checkout($post['mob_no'],$post['amount'],"Testref","Testdesc");
                $mpesa_simulate_res=json_decode($mpesa_simulate);
                 $simulate_checkreqid=$mpesa_simulate_res->CheckoutRequestID;

                // Lipa na mpesa Query (Mpesa Express)
                var_dump($this->mpesa->checkout_query($simulate_checkreqid));

                // Validation
                var_dump($this->mpesa->C2B_REGISTER("Completed")); 

                //c2b
                $c2b_samplecode=$this->mpesa->C2B($post['amount'],$post['mob_no'],$post['ref_id']); // User
               // echo $c2b_samplecode;
                $json = json_decode($c2b_samplecode);

                $c2b_transaction_id = $json->OriginatorCoversationID;
  
                //C2B Transaction
                // var_dump($this->mpesa->transactionstatus($c2b_transaction_id,$c2b_transaction_id,"600998","msisdn","test",null));
            }


	public function setlocation()
    {
        $post = $this->input->post();		
        $this->session->set_userdata('latitude',$post['latitude']);
        $this->session->set_userdata('longitude',$post['longitude']);
        $this->session->set_userdata('address',$post['address']);
        $this->session->set_userdata('distance',$post['distance']);
		$this->session->set_userdata('user_latitude',$post['latitude']);
        $this->session->set_userdata('user_longitude',$post['longitude']);
        $this->session->set_userdata('user_distance',$post['distance']);
		$this->session->set_userdata('user_address',$post['address']);
		$location = explode(',', $_POST['address']);
		$city_count = $this->db->like('name', $location[0], 'after')->from('city')->count_all_results();
		$this->session->set_userdata('current_location', $location[0]);
    }
    public function clearlocation()
    {
        $this->session->set_userdata('latitude','');
        $this->session->set_userdata('longitude','');
        $this->session->set_userdata('address','');
        $this->session->set_userdata('distance','');
		$this->session->set_userdata('user_latitude','');
        $this->session->set_userdata('user_longitude','');
        $this->session->set_userdata('user_distance','');
		$this->session->set_userdata('user_address','');	
    }
    public function contact() {
        $this->data['page'] = 'contact';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function pages($param)
    {
        $param                    = rawurldecode(utf8_decode($param));
        $query                    = $this->db->query("SELECT * FROM `footer_submenu` WHERE `footer_submenu` = '$param'; ");
        $this->data['list']       = $query->row_array();
        $this->data['module']     = 'pages';
        $this->data['page']       = 'page';
        $this->data['page_title'] = $param;
        $this->load->vars($this->data);
        $this->load->view('user/template');
    }

    public function services() { 
        $conditions['returnType'] = 'count';
        $inputs = array();
        $type = $this->session->userdata('usertype');
        $userId = $this->session->userdata('id');

        if (!empty($this->uri->segment('2'))) {

            $category_name = $this->uri->segment('2');
            $category = $this->home->get_category_slug($category_name);
            $inputs['categories'] = $category;
            $this->data['category_id'] = $category;
        }
		if (!empty($this->uri->segment('3'))) {

            $subcategory_name = $this->uri->segment('3');
            $subcategory = $this->home->get_subcategory_slug($subcategory_name);
            $inputs['subcategories'] = $subcategory;
            $this->data['subcategory_id'] = $subcategory;
        }
		
		if (!empty($this->uri->segment('4'))) {
			$sub_subcategory_name = str_replace('-', ' ', $this->uri->segment('4'));
			$subsubcategory_id = $this->home->get_subsubcategory_id($sub_subcategory_name);
			$inputs['sub_subcategories'] = $subsubcategory_id;
			$this->data['subsubcategory_id'] = $subsubcategory_id;
		}
		
		if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
			$inputs['categories'] = $category;
            $this->data['category_id'] = $category;
			$subcategory = $this->db->select('subcategory')->where('id',$user_id)->get('providers')->row()->subcategory;
			$inputs['subcategories'] = $subcategory;
            $this->data['subcategory_id'] = $subcategory;
		}

        if (isset($_POST) && !empty($_POST)) {
            $inputs['price_range'] = $this->input->post('price_range');
            $inputs['sort_by'] = $this->input->post('sort_by');
            $inputs['common_search'] = $this->input->post('common_search');
            $inputs['categories'] = $this->input->post('categories');
            $inputs['service_latitude'] = $this->input->post('user_latitude');
            $inputs['service_longitude'] = $this->input->post('user_longitude');
            $inputs['user_address'] = $this->input->post('user_address');
			$inputs['sub_subcategories'] = $this->input->post('sub_subcategories');
			$inputs['subcategories'] = $this->input->post('subcategories');
			
        }
        //check session
        $user_address = $this->session->userdata('current_location');
        $service_latitude = $this->session->userdata('latitude');
        $service_longitude = $this->session->userdata('longitude');
        if ($user_address!='') {
            $inputs['user_address'] = $user_address;
            $_POST['user_address'] = $user_address;
        }
        if ($service_latitude!='') {
            $inputs['service_latitude'] = $service_latitude;
            $_POST['user_latitude'] = $service_latitude;
        }
        if ($service_longitude!='') {
            $inputs['service_longitude'] = $service_longitude;
            $_POST['user_longitude'] = $service_longitude;
        }
        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 

        $conditions = array(
            'limit' => $this->perPage
        );

        $this->data['module'] = 'services';
        $this->data['page'] = 'index';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
	    $this->data['count'] = $totalRec;
        if ($type == 'user') 
        {
            $userdet = $this->db->where('id', $userId)->get('users')->row_array();
            $gender_type = $userdet['gender'];
            $this->data['category'] = $this->home->get_category_by_gender($gender_type);
        }
        else
        {
            $this->data['category'] = $this->home->get_category();
        }
		if (!empty($this->uri->segment('2'))) {
			$this->data['subcategory'] = $this->home->get_subcategory($category);
		}else{
			$this->data['subcategory'] = $this->home->get_subcategoryy();
		}	

		//Sub Sub Category
		if (!empty($this->uri->segment('2'))) {
			$this->data['sub_subcategory'] = $this->home->get_subsubcategory($category);
		} else{
			$this->data['sub_subcategory'] = $this->home->get_subsubcategory();
		}
				
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function featured_services() {
        $conditions['returnType'] = 'count';
        $inputs = array();
        if (!empty($this->uri->segment('2'))) {
            $category_name =  $this->uri->segment('2');
            $category = $this->home->get_category_slug($category_name);
            $inputs['categories'] = $category;
            $this->data['category_id'] = $category;
        }

        if (isset($_POST) && !empty($_POST)) {
            $inputs['price_range'] = $this->input->post('price_range');
            $inputs['sort_by'] = $this->input->post('sort_by');
            $inputs['common_search'] = $this->input->post('common_search');
            $inputs['categories'] = $this->input->post('categories');
            $inputs['service_latitude'] = $this->input->post('user_latitude');
            $inputs['service_longitude'] = $this->input->post('user_longitude');
        }




        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 

        $conditions = array(
            'limit' => $this->perPage
        );


        $this->data['module'] = 'services';
        $this->data['page'] = 'index';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
        $this->data['count'] = $totalRec;
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

     public function offered_services() {
        $conditions['returnType'] = 'count';
        $inputs = array();
        if (!empty($this->uri->segment('2'))) {
            $category_name =  $this->uri->segment('2');
            $category = $this->home->get_category_slug($category_name);
            $inputs['categories'] = $category;
            $this->data['category_id'] = $category;
        }

        if (isset($_POST) && !empty($_POST)) {
            $inputs['price_range'] = $this->input->post('price_range');
            $inputs['sort_by'] = $this->input->post('sort_by');
            $inputs['common_search'] = $this->input->post('common_search');
            $inputs['categories'] = $this->input->post('categories');
            $inputs['service_latitude'] = $this->input->post('user_latitude');
            $inputs['service_longitude'] = $this->input->post('user_longitude');
        }




        $totalRec = $this->home->get_all_service($conditions, $inputs);

        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['link_func'] = 'getData';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 

        $conditions = array(
            'limit' => $this->perPage
        );


        $this->data['module'] = 'services';
        $this->data['page'] = 'offered_services';
        $this->data['service'] = $this->home->get_offered_services();
        $this->data['count'] = $totalRec;
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function all_services() {
        extract($_POST);
        $conditions['returnType'] = 'count';
        $inputs['min_price'] = $min_price;
        $inputs['max_price'] = $max_price;
        $inputs['sort_by'] = $this->input->post('sort_by');
        $inputs['common_search'] = $this->input->post('common_search');
        $inputs['category_slug'] = $this->input->post('category_slug');
        $inputs['categories'] = $this->input->post('categories');
		$inputs['subcategories'] = $this->input->post('subcategories');
        $inputs['service_latitude'] = $this->input->post('service_latitude');
        $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['user_address'] = $this->input->post('user_address');
		$inputs['sub_subcategories'] = $this->input->post('sub_subcategories');
		
        $totalRec = $this->home->get_all_service($conditions, $inputs);
        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 

        $conditions = array(
            'limit' => $this->perPage
        );
        $this->data['module'] = 'services';
        $this->data['page'] = 'ajax_service';
        $this->data['service'] = $this->home->get_all_service($conditions, $inputs);
        $result['count'] = $totalRec;
        $result['service_details'] = $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page'], $this->data, TRUE);
        echo json_encode($result);
    }

    function ajaxPaginationData() {
        // Define offset 
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        // Get record count 
        $conditions['returnType'] = 'count';
		
		$inputs['min_price'] = $min_price;
        $inputs['max_price'] = $max_price;
        $inputs['sort_by'] = $this->input->post('sort_by');
        $inputs['common_search'] = $this->input->post('common_search');
        $inputs['categories'] = $this->input->post('categories');
		$inputs['subcategories'] = $this->input->post('subcategories');
        $inputs['service_latitude'] = $this->input->post('service_latitude');
        $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['user_address'] = $this->input->post('user_address');
		$inputs['sub_subcategories'] = $this->input->post('sub_subcategories');
		
		
		$totalRec = $this->home->get_all_service($conditions,$inputs);

        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationData');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 
        $conditions = array(
            'start' => $offset,
            'limit' => $this->perPage
        );

        // Load the data list view 
        $this->data['module'] = 'services';
        $this->data['page'] = 'ajax_service';
		$this->data['service'] = $this->home->get_all_service($conditions,$inputs);
        $result['count'] = $totalRec;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page']);
    }
	
	/* Shop Search */
	public function all_services_shop() {
        extract($_POST);
        $conditions['returnType'] = 'count';
        $inputs['min_price'] = $min_price;
        $inputs['max_price'] = $max_price;
        $inputs['sort_by'] = $this->input->post('sort_by');
        $inputs['common_search'] = $this->input->post('common_search');
        $inputs['categories'] = $this->input->post('categories');
		$inputs['subcategories'] = $this->input->post('subcategories');
        $inputs['service_latitude'] = $this->input->post('service_latitude');
        $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['user_address'] = $this->input->post('user_address');
		
        $totalRec = $this->home->get_allshops($conditions, $inputs);

        // Pagination configuration 
        $config['target'] = '#dataListShop';
        $config['link_func'] = 'getDataShop';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationDataShop');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
		$config['page_for'] = "ShopSearch";

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 

        $conditions = array(
            'limit' => $this->perPage
        );
        $this->data['module'] = 'services';
        $this->data['page'] = 'ajax_service_shop';
        $this->data['shops'] = $this->home->get_allshops($conditions, $inputs);
        $result['count'] = $totalRec;
        $result['shop_details'] = $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page'], $this->data, TRUE);
        echo json_encode($result);
    }
	function ajaxPaginationDataShop() {
        // Define offset 
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        // Get record count 
        $conditions['returnType'] = 'count';
		
		$inputs['min_price'] = $min_price;
        $inputs['max_price'] = $max_price;
        $inputs['sort_by'] = $this->input->post('sort_by');
        $inputs['common_search'] = $this->input->post('common_search');
        $inputs['categories'] = $this->input->post('categories');
		$inputs['subcategories'] = $this->input->post('subcategories');
        $inputs['service_latitude'] = $this->input->post('service_latitude');
        $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['user_address'] = $this->input->post('user_address');
		$inputs['sub_subcategories'] = $this->input->post('sub_subcategories');
				
		
		$totalRec = $this->home->get_allshops($conditions, $inputs);

        // Pagination configuration 
        $config['target'] = '#dataListShop';
        $config['link_func'] = 'getDataShop';
        $config['loading'] = '<img src="' . base_url() . 'assets/img/loader.gif" alt="" />';
        $config['base_url'] = base_url('home/ajaxPaginationDataShop');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
		$config['page_for'] = "ShopSearch";

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 
        $conditions = array(
            'start' => $offset,
            'limit' => $this->perPage
        );

        // Load the data list view 
        $this->data['module'] = 'services';
        $this->data['page'] = 'ajax_service_shop';
		$this->data['shops'] = $this->home->get_allshops($conditions, $inputs);
        $result['count'] = $totalRec;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page']);
    }
	
	
	/*Shop Search*/
	

    public function service_preview() {

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
			
			if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
				$MyShop = $this->db->where('md5(id)', $_GET['sid'])->where('user_id', $this->session->userdata('id'))->get('services')->num_rows();
				if($MyShop == 0){
					redirect(base_url() . "my-services");
				}
			}
			
            extract($_GET);
            $inputs = array();
			$inputs['id'] = $_GET['sid'];
			
			if (isset($_GET['uid']) && !empty($_GET['uid'])) {
				$inputs['uid'] = $_GET['uid'];
				$this->data['service'] = $service = $this->home->get_servicedetails($inputs);
			} else{
				$this->data['service'] = $service = $this->home->get_service_details($inputs);
			}
			
			
            $this->data['module'] = 'service_preview';
            $this->data['page'] = 'index';
            
            $this->load->model('service_model', 'service');
            $this->data['service_image'] = $this->service->service_image($service['id']);
            $this->data['service_offered'] = $this->db->where('service_id', $service['id'])->from('service_offered')->get()->result_array();
            
			$this->data['popular_service'] = $this->home->popular_service_list($service);
			
            if (!empty($service['id'])) {
                $this->views($this->data['service']);
            }
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        } else {
            redirect(base_url());
        }
    }

    private function views($inputs) {
        $service_id = $inputs['id'];
        $user_id = rand(1, 100);

        $this->db->select('id');
        $this->db->from('views');
        $this->db->where('user_id', $user_id);
        $this->db->where('service_id', $service_id);
        $check_views = $this->db->count_all_results();

        $this->db->select('id');
        $this->db->from('services');
        $this->db->where('user_id', $user_id);
        $this->db->where('id', $service_id);
        $check_self_gig = $this->db->count_all_results();

        if ($check_views == 0 && $check_self_gig == 0) {
            $this->db->insert('views', array('user_id' => $user_id, 'service_id' => $service_id));

            $this->db->set('total_views', 'total_views+1', FALSE);
            $this->db->where('id', $service_id);
            $this->db->update('services');
        }
    }

    public function get_common_search_value() {
        if (isset($_GET['term'])) {
            $search_value = $_GET['term'];
            $this->db->select("s.service_title,s.service_location,s.service_offered,c.category_name");
            $this->db->from('services s');
            $this->db->join('categories c', 'c.id = s.category', 'LEFT');
            $this->db->where("s.status = 1");
            $this->db->group_start();
            $this->db->like('s.service_title', $search_value);
            $this->db->or_like('s.service_location', $search_value);
            $this->db->or_like('c.category_name', $search_value);
            $this->db->group_end();
            $result = $this->db->get()->result_array();
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = ucfirst($row['service_title']);
                $arr_result[] = ucfirst($row['category_name']);

                echo json_encode($arr_result);
            }
        }
    }

    public function user_dashboard() {
        $this->data['page'] = 'user_dashboard';
        $this->data['category'] = $this->home->get_category();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function add_service() {

        $this->data['page'] = 'add_service';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_bookings() {
        $this->data['page'] = 'user_bookings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_notifications() {
        $this->data['page'] = 'user_notifications';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_favourites() {
        $this->data['page'] = 'user_favourites';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_settings() {
        $this->data['page'] = 'user_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_reviews() {
        $this->data['page'] = 'user_reviews';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_chats() {
        $this->data['page'] = 'user_chats';
        $this->data['server_name'] = settingValue('server_name');
        $this->data['port_no'] = settingValue('port_no');
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_services() {
        $this->data['page'] = 'prof_services';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_service_detail() {
        $this->data['page'] = 'prof_service_detail';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function prof_packages() {
        $this->data['page'] = 'prof_packages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function set_location() {
        $details = array('user_address' => $this->input->post('address'),
            'user_latitude' => $this->input->post('latitude'),
            'user_longitude' => $this->input->post('longitude'),
			);
        $this->session->set_userdata($details);
		$location = explode(',', $this->input->post('address'));
		$city_count = $this->db->like('name', $location[0], 'after')->from('city')->count_all_results();
    }

    public function current_location() {
        if (!empty($_POST['location'])) {
            $location = explode(',', $_POST['location']); 
            $city_count = $this->db->like('name', $location[0], 'after')->from('city')->count_all_results();
            if ($city_count >= 1) {
                $this->session->set_userdata('current_location', $location[0]);
				$this->session->set_userdata('user_latitude', $_POST['latitude']);
				$this->session->set_userdata('user_longitude', $_POST['longitude']);	
                echo 1;
            } else {
                echo 2;
            }
        }
    }

    public function clear_all_noty() {
        if (!empty($_POST['id'])) {
            $user_type = $this->session->userdata('usertype');
            $res = $this->db->where('receiver=', $_POST['id'])->update('notification_table', ['status' => 0]);
            if ($res == true) {
                echo json_encode(['success' => true, 'msg' => 'cleared']);
                exit;
            } else {
                echo json_encode(['success' => false, 'msg' => 'not cleared']);
                exit;
            }
        }
    }

    public function clear_all_chat() {
        if (!empty($_POST['id'])) {
            $user_type = $this->session->userdata('usertype');
            $res = $this->db->where('receiver_token=', $_POST['id'])->update('chat_table', ['read_status' => 1]);
            if ($res == true) {
                echo json_encode(['success' => true, 'msg' => 'cleared']);
                exit;
            } else {
                echo json_encode(['success' => false, 'msg' => 'not cleared']);
                exit;
            }
        }
    }
    public function grk_Week_Range($DateString, $FirstDay)
    {
        
        $monday = strtotime("last sunday");
        $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
        $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
        $this_week_sd = date("Y-m-d",$monday);
        $this_week_ed = date("Y-m-d",$sunday);

        #   On renvoie les données
        return array('start_date'=>$this_week_sd,'end_date'=>$this_week_ed);
    }
    public function providers_weekly_report()
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('products_model');
        //dates
        $cw_dates = $this->grk_Week_Range(date("Y-m-d H:i:s"), 0);
        //get all providers
        $providers = $this->products_model->getsingletabledata('providers', ['status'=>1, 'delete_status'=>0, 'type'=>1], '', 'id', 'asc', 'multiple');

        $bookings = [];
        $i = 0;
        if (!empty($providers)) 
        {
            foreach ($providers as $val) 
            {
                $wbook = ['book_service.provider_id'=>$val['id'], "DATE_FORMAT(book_service.updated_on, ('%Y-%m-%d')) >="=>$cw_dates['start_date'], "DATE_FORMAT(book_service.updated_on, ('%Y-%m-%d')) <="=>$cw_dates['end_date']];
                $wbook_in = ['column_name'=>'book_service.status', 'value'=>[5,6,7]];
                $bdetails = $this->home->provider_bookings($wbook, $wbook_in);
                //orders
                $worder = ['product_cart.status'=>1, 'shops.provider_id'=>$val['id'], "DATE_FORMAT(product_cart.updated_at, ('%Y-%m-%d')) >="=>$cw_dates['start_date'], "DATE_FORMAT(product_cart.updated_at, ('%Y-%m-%d')) <="=>$cw_dates['end_date']];
                $win = ['column_name'=>'product_cart.delivery_status', 'value'=>[5,6]];
                $odetails = $this->home->provider_orders($worder, $win);
                if (!empty($bdetails) || !empty($odetails)) 
                {
                    $bookings[$i]['id'] = $val['id'];
                    $bookings[$i]['name'] = $val['name'];
                    $bookings[$i]['email'] = $val['email'];
                    $bookings[$i]['bookings'] = $bdetails;
                    $bookings[$i]['orders'] = $odetails;
                    $i++;
                }
            }
        }
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $phpmail_config=settingValue('mail_config');
        if(isset($phpmail_config)&&!empty($phpmail_config))
        {
            if($phpmail_config=="phpmail")
            {
              $from_email=settingValue('email_address');
            }
            else
            {
              $from_email=settingValue('smtp_email_address');
            }
        }
        $this->load->library('email');
        
        if (!empty($bookings)) 
        {
           foreach ($bookings as $val) 
           {
               $data['invoice'] = $val;
               $dt = time();
               $html = $this->load->view('user/home/provider_revenue', $data, true);
               $mpdf->writeHTML($html);
               $content = ($mpdf->Output('Revenue - $dt', 'S'));
               $tomail = $val['email'];
               $mail = $this->email
                    ->from($from_email)
                    ->to($tomail)
                    ->attach($content, 'attachment', "Revenue - $dt.pdf", 'application/pdf')
                    ->subject('Revenue -'.date("Y-m-d"))
                    ->message('Hi '.$val['name'])
                    ->send();
           }
        }
        echo "success";
    }
    public function providers_daily_report()
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('products_model');
        //get all providers
        $providers = $this->products_model->getsingletabledata('providers', ['status'=>1, 'delete_status'=>0, 'type'=>1], '', 'id', 'asc', 'multiple');
        $bookings = [];
        $i = 0;
        if (!empty($providers)) 
        {
            foreach ($providers as $val) 
            {
                $wbook = ['book_service.provider_id'=>$val['id'], "DATE_FORMAT(book_service.updated_on, ('%Y-%m-%d')) ="=>date("Y-m-d")];
                $wbook_in = ['column_name'=>'book_service.status', 'value'=>[5,6,7]];
                $bdetails = $this->home->provider_bookings($wbook, $wbook_in);
                //orders
                $worder = ['product_cart.status'=>1, 'shops.provider_id'=>$val['id'], "DATE_FORMAT(product_cart.updated_at, ('%Y-%m-%d')) ="=>date("Y-m-d")];
                $win = ['column_name'=>'product_cart.delivery_status', 'value'=>[5,6]];
                $odetails = $this->home->provider_orders($worder, $win);
                if (!empty($bdetails) || !empty($odetails)) 
                {
                    $bookings[$i]['id'] = $val['id'];
                    $bookings[$i]['name'] = $val['name'];
                    $bookings[$i]['email'] = $val['email'];
                    $bookings[$i]['bookings'] = $bdetails;
                    $bookings[$i]['orders'] = $odetails;
                    $i++;
                }

            }
            
        }
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        //Mail
        $phpmail_config=settingValue('mail_config');
        if(isset($phpmail_config)&&!empty($phpmail_config))
        {
            if($phpmail_config=="phpmail")
            {
              $from_email=settingValue('email_address');
            }
            else
            {
              $from_email=settingValue('smtp_email_address');
            }
        }
        $this->load->library('email');
        //
        if (!empty($bookings)) 
        {
           foreach ($bookings as $val) 
           {
               $data['invoice'] = $val;
               $dt = time();
               $html = $this->load->view('user/home/provider_revenue', $data, true);
               $mpdf->writeHTML($html);
               $content = ($mpdf->Output('Revenue - $dt', 'S'));
               $tomail = $val['email'];
               $mail = $this->email
                    ->from($from_email)
                    ->to($tomail)
                    ->attach($content, 'attachment', "Revenue - $dt.pdf", 'application/pdf')
                    ->subject('Revenue -'.date("Y-m-d"))
                    ->message('Hi '.$val['name'])
                    ->send();
           }
        }
        echo "success";
    }

    public function ajaxSearch() {
        if ($this->input->post('service_title')) {
            $stmt =  $this->home->fetch_data($this->input->post('service_title'));
        }
        echo $stmt;
    }

    public function admin_login() {
        $admin_details = $this->db->get_where('administrators', array('role'=> 1))->row_array();
        $session_data = array( 
          'id' => $admin_details['user_id'],
          'chat_token' => $admin_details['token'],
          'name'  => $admin_details['username'], 
          'email'     => $admin_details['email'], 
          'usertype' => 'admin'
        ); 
        $this->session->set_userdata($session_data); 
        redirect(base_url());
    }

}
