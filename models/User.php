<?php
Class User extends CI_Model
{
	function login($username, $password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	function addLog($data)
	{
		$this->db->insert('user_log', $data);
		return true;
	}
	
	function getUser($email)
	{
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		$data = $query->row_array();
		return $data['displayname'];
	}
	
	function getUseremail($email)
	{
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		$data = $query->row_array();
		return $data;
	}
	
	function checkoldpass($oldpass)
	{
		$this->db->select('*');
		$this->db->where('password', $oldpass);
		$query = $this->db->get('users');
		$data = $query->row_array();
		return $data;
	}

	function getAllUser($data = array())
	{
		$query = $this->db->get('users');

		$columnArray = array (
			"id",
			"name",
			"email",
			"role");
		$this->db->select('*');
		$this->db->from('users');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'role like "%'.$data['search']['value'].'%"'
			);			
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();		
		return $query->result_array();
	}

	function getAllUserCount($data = array())
	{
		$query = $this->db->get('users');

		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('users');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'role like "%'.$data['search']['value'].'%"'
			);
		}
		$query = $this->db->get();		
		return $query->result_array();
	}

	function addUser($data)
	{
		$this->db->insert('users', $data);
		return true;
	}

	function getUserById($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get('users');
		$data = $query->row_array();
		return $data;
	}
	
	function updateUser($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		return true;
	}
	
	function deleteUser($id)
	{
		$this->db->delete('users', array('id' => $id)); 
		return true;
	}
	
	function getAllRole()
	{
		$query = $this->db->get('userroles');
		$data = $query->result_array();
		return $data;
	}

	function getAllLogs($data = array())
	{
		$query = $this->db->get('user_log');

		$columnArray = array (
			"username",
			"action",
			"ipaddress",
			"datetime",
			"user_role");
		$this->db->select('*');
		$this->db->from('user_log');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'username like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'action like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ipaddress like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'datetime like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
			);

			$this->db->or_where(
				'status like "%'.$data['search']['value'].'%"'
			);
		}
		
		if (isset($data) && isset($data['columns'][4]['search']['value'])
               && $data['columns'][4]['search']['value'] != ''
           ) {
				$this->db->where('user_role', $data['columns'][4]['search']['value']);               
           }

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();		
		return $query->result_array();
	}

	function getAllLogsCount($data = array())
	{
		$query = $this->db->get('user_log');

		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('user_log');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'username like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'action like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ipaddress like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'datetime like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
			);

			$this->db->or_where(
				'status like "%'.$data['search']['value'].'%"'
			);
		}
		
		if (isset($data) && isset($data['columns'][4]['search']['value'])
               && $data['columns'][4]['search']['value'] != ''
           ) {
				$this->db->where('user_role', $data['columns'][4]['search']['value']);               
           }

		$query = $this->db->get();		
		return $query->result_array();
	}
	function addsearchterm($data) {
		$this->db->insert('search', $data);
		return true;
	}
		
	function getheadersearch($data = array())
	{
		$query = $this->db->get('search');

		$columnArray = array (
			"searchterm",
			"ip",
			"ip_location" ,
			"date");
		$this->db->select('*');
		$this->db->from('search');
		if (isset($data) && isset($data['columns'][0]['search']['value'])
               && $data['columns'][0]['search']['value'] == '0'
           ) {
				$this->db->where('searchterm !=', NULL);
        }
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'searchterm like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_location like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'searchterm like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
			);
		}

		

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function getAllSearchCount($data = array())
	{
		$query = $this->db->get('search');

		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('search');
		if (isset($data) && isset($data['columns'][0]['search']['value'])
               && $data['columns'][0]['search']['value'] == '0'
           ) {
				$this->db->where('searchterm !=', '');
        }
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'searchterm like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_location like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'searchterm like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
			);
		}
		$query = $this->db->get();		
		return $query->result_array();
	}


	function getAmazondata($data = array()) {

		$this->db->select('*');
		$this->db->from('amazon_order');

        $columnArray = array("id", "date_time", "unit", "orderid" ,"customerdata" ,"shippingprovider","trackingnumber" ,"status");

        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'date_time like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );
            $this->db->or_where(
                'unit like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'orderid like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'customerdata like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'shippingprovider like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'trackingnumber like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'status like "%'.$data['search']['value'].'%"'
            );
        }

        if (isset($data['order'])) {
            $this->db->order_by(
                $columnArray[$data['order'][0]['column']],
                $data['order'][0]['dir']
            );
        }
        $this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();        
        return $query->result_array();
	}

	function getAmazondataCount($data = array()) {
		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('amazon_order');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'date_time like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );
            $this->db->or_where(
                'unit like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'orderid like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'customerdata like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'shippingprovider like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'trackingnumber like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'status like "%'.$data['search']['value'].'%"'
            );
        }
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function addUrlredirect($data)
	{ 
		$this->db->insert('redirect_url', $data);
		return true;
	}
	
	function updateUrlredirect($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('redirect_url', $data);
		return true;
	}
	
	function geturlredirectById($id)
	{
		$this->db->select('*'); 
		$this->db->where('id', $id);
		$query = $this->db->get('redirect_url');
		$data = $query->row_array();
		return $data;
	}

    /**
     * @param array $data
     * @return array
     */
	function getAllredirecturl($data = array())
	{
		$columnArray = array (
			"id",
			"slug",
			"redirect",
			"category" ,
			"visit" ,
			"status" ,
			"category");

        $this->db->select('*, (SELECT count(*) as total FROM urlredirect_visit ru WHERE ru.slug = redirect_url.slug) AS visit');
		$this->db->from('redirect_url');

		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'slug like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'redirect like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'category like "%'.$data['search']['value'].'%"'
			);
            $this->db->or_where(
				'status like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'(SELECT count(*) as total FROM urlredirect_visit ru WHERE ru.slug = redirect_url.slug) = "'.(int) $data['search']['value'].'"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	function getAllredirecturlCount()
	{
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'slug like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'redirect like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'category like "%'.$data['search']['value'].'%"'
            );
        }
        $this->db->from('redirect_url');
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function deleteurlredirect($id)
	{
		$this->db->delete('redirect_url', array('id' => $id)); 
		return true;
	}
	
	function geturlredirectBySlug($slug)
	{
		$this->db->select('*'); 
		$this->db->where('slug', $slug);
		$this->db->where('status', 'active');
		$query = $this->db->get('redirect_url');
		$data = $query->row_array();
		return $data;
	}

	function Urlredirectvisit($data)
	{ 
		$this->db->insert('urlredirect_visit', $data);
		return true;
	}
		
	function getUrlRedirectVisit($slug)
	{
		$this->db->select('count(*) as total');
		$this->db->where('slug', $slug);
		$query = $this->db->get('urlredirect_visit');
		$data = $query->row_array();
		return $data['total'];
	}
	function addFeedbackData($data)
	{ 
		$this->db->insert('support', $data);
		return true;  
	}

	function getsupportdataByuserID($id)
	{
		$this->db->select('*');
		$this->db->where('user_id', $id);		
		$query = $this->db->get('support');
		$data = $query->result_array();
		return $data;
	}
	function addnotfound($data){
		$this->db->insert('notfound_data', $data);
		return $this->db->insert_id();
	}

    /**
     * @param array $data
     *
     * @return mixed
     */
	function getAllnotFound($data = array())
	{
        // print_r($data);exit;
        $columnArray = array (
        	"id",
         	"page",
	        "referal",
    	    "date" ,
        	"ip" ,
         	"country" ,
         	"browser" ,
         	"os" ,
         	"os");
		$this->db->select('*');
		$this->db->from('notfound_data');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'page like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'referal like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'date like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );

            $this->db->or_where(
                'ip like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'country like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'browser like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'os like "%'.$data['search']['value'].'%"'
            );
        }

        if (isset($data['order'])) {
			$this->db->order_by(
                $columnArray[$data['order'][0]['column']],
                $data['order'][0]['dir']
            );
        }
        $this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();        
		return $query->result_array();
	}
    /**
     *
     * @param array $data
     *
     * @return mixed
     */
    function getAllNotFoundCount($data = array())
    {
        $this->db->select('COUNT(*) as aggregate');

        $this->db->from('notfound_data');

        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'page like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'referal like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'date like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );

            $this->db->or_where(
                'ip like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'country like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'browser like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'os like "%'.$data['search']['value'].'%"'
            );
        }

        $query = $this->db->get();
        $num_rows = $query->result_array();
        return $num_rows;
    }

	function createnewslug($table_name,$redirect_slug,$slug)
	{
		$query = "SELECT COUNT(*) AS NumHits FROM $table_name WHERE  $redirect_slug  LIKE '$slug%'";
		$query=$this->db->query($query);
		$result = $query->row();
		$numHits=$result->NumHits;
		return ($numHits > 0) ? ($slug . '-' . $numHits) : $slug;
	}

	function getUrlRedirectVisitchart($id)
	{
		$this->db->select('*, count(id) as total');
		$this->db->where('slugID', $id);
		$this->db->where('date_time BETWEEN "'.  date('Y-m-01 00:00:00'). '" and "'. date('Y-m-d 23:59:59').'"');
		$this->db->group_by('date(date_time)');
		$query = $this->db->get('urlredirect_visit');
		return  $query->result_array();
	}
	
	function getUrlRedirectVisitchartpia($id)
	{
		$this->db->select('*, count(id) as total');
		$this->db->where('slugID', $id);
		$this->db->group_by('ip_location');
		$query = $this->db->get('urlredirect_visit');
		return  $query->result_array();
	}

    /**
     * Delete perticular record from notfound_data table
     * @param $id
     * @return bool
     */
	function ignoreUrl($id)
	{
		$this->db->delete('notfound_data', array('id' => $id));
		return true;
	}

	function getignordataByIgnorID($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get('notfound_data');
		$data = $query->result_array();
		return $data;
	}

	function insertignordata($data)
	{
		$this->db->insert('ignor_url', $data);
		return true;
	}

	function getignordataByIgnorpage($page)
	{
		$this->db->select('*');
		$this->db->where('url', $page);
		$query = $this->db->get('ignor_url');
		$data = $query->result_array();
		return $data;
	}

	function getUserLoginCount($username) {

		$this->db->select('count(*) as total');
		$this->db->where('username', $username);
		$this->db->where('action', "Successful Login");
		$query = $this->db->get('user_log');
		$data = $query->row_array();
		return $data;
	}
	 /**
     * @param array $data
     *
     * @return mixed
     */
     function getMessageAllLogsPopup()
	{
		$this->db->select('*');
		$this->db->from('message_log');
		$this->db->where('status','1');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getMessageAllLogs($data = array())
	{		
        $columnArray = array (
         	"date_time",
    	    "origin" ,
        	"email_address" ,
         	"subject");
		$this->db->select('*');
		$this->db->from('message_log');
		$this->db->where('status','1');
		 if (isset($data) && isset($data['columns'][4]['search']['value'])
               && $data['columns'][4]['search']['value'] != ''
           ) {				
           }
        else {
		$this->db->where('origin !=','URL Redirects');
		}
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->where(
                'date_time like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );
            $this->db->or_where(
                'origin like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'email_address like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'subject like "%'.$data['search']['value'].'%"'
            );            
        }
        
        if (isset($data['order'])) {
			$this->db->order_by(
                $columnArray[$data['order'][0]['column']],
                $data['order'][0]['dir']
            );
        }
        $this->db->limit($data['length'], $data['start']);
        
        $query = $this->db->get();
		return $query->result_array();
	}	
    /**
     *
     * @param array $data
     *
     * @return mixed
     */
    function getMessageLogCount($data = array())
    {		
        $this->db->select('COUNT(*) as aggregate');
        $this->db->from('message_log');
		$this->db->where('status','1');
		if (isset($data) && isset($data['columns'][4]['search']['value']) && $data['columns'][4]['search']['value'] != '') {			
        }
        else {
			$this->db->where('origin !=','URL Redirects');
		}
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'date_time like "%'.date('Y-m-d H:i:s', strtotime($data['search']['value'])).'%"'
            );
            $this->db->or_where(
                'origin like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'email_address like "%'.$data['search']['value'].'%"'
            );
            $this->db->or_where(
                'subject like "%'.$data['search']['value'].'%"'
            );            
        }
        
        $query = $this->db->get();
        $num_rows = $query->result_array();
        return $num_rows;
    }

	function insertMessageLogdata($data) 
	{
		$this->db->insert('message_log', $data);		
		$insert_id_log = $this->db->insert_id();
		return $insert_id_log;
	}
	
	function getRegisterEmailId($email) {
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email',$email);		
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function base64_to_jpeg($base64_string, $output_file,$id) {
			
			$ifp = fopen($output_file, "wb"); 
			$data = explode(',', $base64_string);
			fwrite($ifp, base64_decode($data[1])); 
			fclose($ifp); 
			return $output_file; 
	}
	
	function fetchShipingStatus($id,$tracking,$shiping,$url){
		$CI =& get_instance();
        
		if(trim($shiping)=="LASERSHIP"){
		
			$doc = new DomDocument;
			// We need to validate our document before refering to the id
			$doc->validateOnParse = true;
			$doc->loadHtml(file_get_contents('http://www.lasership.com/track/'.$tracking));
			$a = $doc->getElementById('progress_bar');
			$status = trim($a->textContent);
			$this->db->where('id', $id);		
			$this->db->update('amazon_order', array('status'=>$status));			
			if(strtolower($status)=="delivered"){
				$curl = curl_init();
					
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => $CI->config->base_url()."browshot/browshot_simple.php",
						CURLOPT_USERAGENT => 'Codular Sample cURL Request',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => array(
							id => $id,
							url => $url
						)
					));					
					$resp = curl_exec($curl);					
					curl_close($curl);				
			}
			return $status;
		}

		if($shiping=="UPS" || $shiping=="USPS" || $shiping=="FedEx"){	

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$CI->config->base_url()."easypost/examples/trackers.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,"shipping=".$shiping."&tracking=".$tracking);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$server_output = curl_exec ($ch);
				
				if($server_output=="pre_transit" || $server_output=="in_transit")
					$server_output = "In Transit";
					
				curl_close ($ch);
				$status = trim(ucfirst($server_output));			
				
				if(strtolower($status)=="delivered"){					
					
					$curl = curl_init();
					
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => $CI->config->base_url()."browshot/browshot_simple.php",
						CURLOPT_USERAGENT => 'Codular Sample cURL Request',
						CURLOPT_POST => 1,
						CURLOPT_POSTFIELDS => array(
							id => $id,
							url => $url
						)
					));					
					$resp = curl_exec($curl);					
					curl_close($curl);
					curl_close ($ch);					
				}
				
				$this->db->where('id', $id);		
				$this->db->update('amazon_order', array('status'=>$status));
				return $status;
		}
		
	}
	
	function opneMailUpdate($id,$data) {
		$this->db->where('id', $id);
		$this->db->update('message_log', $data);
		return true;
	}
	
	function comingSoon($data) {
		$this->db->insert('coming_soon',$data);
		return true;
	}
	
	function addslider($data) {
		$this->db->insert('slider',$data);
		return true;
	}
	
	function getAllslider() {
		$this->db->select('*');
		$this->db->from('slider');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getSliderById($id) {
		$this->db->select('*');
		$this->db->from('slider');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}
	
	function updateslider($data,$id) {
		$this->db->where('id',$id);
		$this->db->update('slider',$data);
		return true;
	}
	
	function deleteslider($id) {
		$this->db->delete('slider', array('id' => $id)); 
		return true;
	}
	 /**
     * @param array $data
     * @return array
     */
	function getAllUrlCategory($data = array())
	{
		$columnArray = array (
			"id",
			"category");

        $this->db->select('*');
		$this->db->from('urlcategory');

		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'category like "%'.$data['search']['value'].'%"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getAllCategoryCount()
	{
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
                'id',
                $data['search']['value']
            );
            $this->db->or_where(
                'category like "%'.$data['search']['value'].'%"'
            );            
        }
        $this->db->from('urlcategory');
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function addUrlCategory($data) {
		$this->db->insert('urlcategory',$data);
		return true;
	}
	
	function updateUrlCategory($id,$data) {
		$this->db->where('id',$id);
		$this->db->update('urlcategory',$data);
		return true;
	}
	
	function getUrlCategoryById($id) {
		$this->db->select('*');
		$this->db->from('urlcategory');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}
	
	function deleteUrlCategory($id) {
		$this->db->delete('urlcategory',array('id' => $id));
		return true;
	}
	
	function getCategoryredirecturl($category)
	{	
		$this->db->select('*');
		$this->db->from('redirect_url');
		$this->db->where("category LIKE '%$category%'");
		if($category == 'ES05') {
		$this->db->where("category !=",'ES05 Launch Sponsors Contest');
		}
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function updatesliderorder($id,$data) {
		$this->db->where('id',$id);
		$this->db->update('slider',$data);		
		return true;
	}

	function getMaxSliderOrder() {
		$this->db->select('MAX(`order`) as order_id');
		$this->db->from('slider');
		$query = $this->db->get();		
		$data = $query->row_array();
		return $data;
	}
	
	function getSliderOrderBy() {
		$this->db->select('*');
		$this->db->from('slider');
		$this->db->where('status','1');
		$this->db->order_by('order','ASC');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function updatesendy($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('sendy', $data);
		return true;
	}
	function getsendyData() {
		$this->db->select('*');
		$this->db->from('sendy');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getAmazonTotalCount() {
		$this->db->select('count(*) as AmazonTotal');
		$this->db->from('amazon_order');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getUserTotalCount() {
		$this->db->select('count(*) as UserTotal');
		$this->db->from('users');
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function getUserTodayCount() {
		$this->db->select('count(*) as UserTodayTotal');
		$this->db->from('users');
		$this->db->where('date_time LIKE',date("Y-m-d%"));
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function getSubscriberTotalCount() {
		$this->db->select('count(*) as SubscribTotal');
		$this->db->from('subscrib');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getSubscriberTodayCount() {
		$this->db->select('count(*) as SubscribTodayTotal');
		$this->db->from('subscrib');
		$this->db->where('date_time LIKE',date("Y-m-d%"));
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function getTotalViewCount() {
		$this->db->select('sum(popular) as TotalVisitors');
		$this->db->from('blog');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function addJournalData($data) {
		$this->db->insert('journal',$data);
		return true;
	}

	function updateJournalData($data,$id) {
		$this->db->where('user_id', $id);
		$this->db->update('journal', $data);
		return true;
	}

	function getJournalDataById($Id) {
		$this->db->select("count(*) as TotalCount");
		$this->db->from('journal');
		$this->db->where('user_id',$Id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function UpdateGoalCount($GoalUpdate,$id) {
		$this->db->where('id',$id);
		$this->db->update('journal_goal_count',$GoalUpdate);
		return true;
	}

	function getUserIdByGoalId($id) {
		$this->db->select("*");
		$this->db->from('journal_goal_count');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getYesNoByGoalId($id) {
		$this->db->select("*");
		$this->db->from('journal_goal_count');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getGoalCountByTodayDate($id) {
		$this->db->select("count(*) as TotalCount");
		$this->db->from('journal_goal_count');
		$this->db->where('id',$id);
		$this->db->where('yes_no','');
		$this->db->where('status','A');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getGoalCountByYes($id) {
		$this->db->select("count(*) as YesCount");
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$this->db->where('yes_no','yes');
		$this->db->where('status','A');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getGoalCountByNo($id) {
		$this->db->select("count(*) as NoCount");
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$this->db->where('yes_no','no');
		$this->db->where('status','A');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getGoalStatus($uid) {
		$this->db->select("count(*) as statuCount");
		$this->db->from('journal');
		$this->db->where('user_id',$uid);		
		$this->db->where('status','1');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getProduct() {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getAllProductTags() {
		$this->db->select('*');
		$this->db->from('productTag');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getAllProductCategories() {
		$this->db->select('*');
		$this->db->from('productCategories');
		$this->db->where('parent_id',0);
		$this->db->where('category_status',0);
		$this->db->order_by('catOrder');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	function getProductById($id) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getTempProductById($id) {
		$this->db->select('*');
		$this->db->from('temp_product');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getProductByCategory($category) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('productCategories',$category);
		$this->db->where('product_status','0');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getProductByTags($tags) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('productTag',$tags);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function addProductComments($data) {
		$this->db->insert('product_comment',$data);
		$insert_id = $this->db->insert_id();		
		return $insert_id;
	}

	function getProductReviewByProductId($id) {
		$this->db->select('*');
		$this->db->from('product_comment');
		$this->db->where('productId',$id);
		$this->db->where('status','1');
		$this->db->order_by('date_time','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getProductMostRecentReviewByProductId($id) {
		$this->db->select('*');
		$this->db->from('product_comment');
		$this->db->where('productId',$id);
		$this->db->where('status','1');
		$this->db->order_by('date_time','desc');
		$this->db->limit(10);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getProductByFilter($min,$max) {
		$min = (int)$min;
		$max = (int)$max;
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('productPrice >=', $min);
		$this->db->where('productPrice <=', $max);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function addwholoesaleinquiry($data) {
		$this->db->insert('whole_sale_inquiry',$data);
		return true;
	}
	
	 /**
     * @param array $data
     * @return array
     */
	function getWholesaleInquirie($data = array())
	{
		$query = $this->db->get('whole_sale_inquiry');
		$columnArray = array (
			"company",
			"website",
			"name",
			"email",
			"about",
			"date",
			"sell",
			"ipaddress",
			"iplocation");

        $this->db->select('*');
		$this->db->from('whole_sale_inquiry');

		if (isset($data['search']['value']) && $data['search']['value'] != '') {
			
			$this->db->or_where(
				'company like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'about like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'date like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'sell like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ipaddress like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'iplocation like "%'.$data['search']['value'].'%"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getWholesaleInquirieCount()
	{	
		$query = $this->db->get('whole_sale_inquiry');
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

            $this->db->or_where(
				'company like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'about like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'date like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'sell like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ipaddress like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'iplocation like "%'.$data['search']['value'].'%"'
			);
        }
        $this->db->from('whole_sale_inquiry');
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function deletewholesaleinquiry($id)
	{
		$this->db->delete('whole_sale_inquiry', array('id' => $id)); 
		return true;
	}
	/* jobs */
	function getAlljobs() {
		$this->db->select('*');
		$this->db->from('job_vacancies');		
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getAlljobsByOder() {
		$this->db->select('*');
		$this->db->from('job_vacancies');
		$this->db->where('status','A');
		$this->db->order_by('order','ASC');		
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function deletejob($id)
	{
		$this->db->delete('job_vacancies', array('id' => $id)); 
		return true;
	}
	
	function updatejob($data,$id) {
		$this->db->where('id',$id);		
		$this->db->update('job_vacancies', $data);
		return true;
	}
	
	function getMaxjobOrder() {
		$this->db->select('MAX(`order`) as order_id');
		$this->db->from('job_vacancies');
		$query = $this->db->get();		
		$data = $query->row_array();
		return $data;
	}
	
	function addJobs($data) {
		$this->db->insert('job_vacancies',$data);
		return true;
	}
	
	function getJobById($id) {
		$this->db->select('*');
		$this->db->from('job_vacancies');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	/* Volunteering */
	function getAllvolunteering() {
		$this->db->select('*');
		$this->db->from('volunteering_vacancies');		
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getAllvolunteeringByOder() {
		$this->db->select('*');
		$this->db->from('volunteering_vacancies');
		$this->db->where('status','A');
		$this->db->order_by('order','ASC');		
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function deletevolunteering($id)
	{
		$this->db->delete('volunteering_vacancies', array('id' => $id)); 
		return true;
	}
	
	function updatevolunteering($data,$id) {
		$this->db->where('id',$id);		
		$this->db->update('volunteering_vacancies', $data);
		return true;
	}
	
	function getMaxvolunteeringOrder() {
		$this->db->select('MAX(`order`) as order_id');
		$this->db->from('volunteering_vacancies');
		$query = $this->db->get();		
		$data = $query->row_array();
		return $data;
	}
	
	function addVolunteering($data) {
		$this->db->insert('volunteering_vacancies',$data);
		return true;
	}
	
	function getVolunteeringById($id) {
		$this->db->select('*');
		$this->db->from('volunteering_vacancies');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getgoallist($data = array())
	{
		$query = $this->db->get('journal');
		$columnArray = array (
			"set_goal",
			"reminder_time");			
		$this->db->select('*');
		$this->db->from('journal');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {
			
			$this->db->or_where(
				'set_goal like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'reminder_time like "%'.$data['search']['value'].'%"'
			);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();		
		return $query->result_array();
	}

	function getAllGoalListCount($data = array())
	{
		$query = $this->db->get('journal');
		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('journal');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'set_goal like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'reminder_time like "%'.$data['search']['value'].'%"'
			);
		}
		$query = $this->db->get();
		return $query->result_array();
	}	

	function getGoalListById($id,$data = array())
	{
		$query = $this->db->get('journal_goal_count');
		$columnArray = array (
			"date",
			"yes_no",
			"ip_address",
			"ip_location");
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);		
		$this->db->where("yes_no !=",'');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {
			$where = '(date like "%'.date('Y-m-d', strtotime($data['search']['value'])).'%" OR yes_no like "%'.$data['search']['value'].'%" OR ip_address like "%'.$data['search']['value'].'%" OR ip_location like "%'.$data['search']['value'].'%")';
			$this->db->where($where);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function getAllGoalListByIdCount($id,$data = array())
	{
		$query = $this->db->get('journal_goal_count');
		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);		
		$this->db->where("yes_no !=",'');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {
			$where = '(date like "%'.date('Y-m-d', strtotime($data['search']['value'])).'%" OR yes_no like "%'.$data['search']['value'].'%" OR ip_address like "%'.$data['search']['value'].'%" OR ip_location like "%'.$data['search']['value'].'%")';
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	function addbrand($data) {
		$this->db->insert('brand_ambassador',$data);
		return true;
	}
	
	function pageInsert($page) {
		$this->db->insert('shop_interaction',$page);		
		return true;
	}

	function getShopInteraction($data = array())
	{
		$query = $this->db->get('shop_interaction');
		$columnArray = array (
			"product",
			"pageView",
			"buttonView");
		$this->db->select('*');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'product like "%'.$data['search']['value'].'%"'
			);
		}
		$this->db->from('shop_interaction');
		$this->db->where('productId !=',NULL);
		$this->db->group_by('productId');
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function getShopInteractionAllCount($data = array())
	{
		$query = $this->db->get('shop_interaction');
		$this->db->select('COUNT(*) AS aggregate,productId');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {
			$this->db->or_where(
				'product like "%'.$data['search']['value'].'%"'
			);
		}
		$this->db->from('shop_interaction');
		$this->db->where('productId !=',NULL);
		$this->db->group_by('productId');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getShopInteractioncount($page) {
		$this->db->select('sum(pageView) as totalPageCount,sum(buttonView) as totalButtonCount');
		$this->db->from('shop_interaction');
		$this->db->where('productId',$page);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}
	
	function getBrandAmbassadorInquiris($data = array())
	{
		$query = $this->db->get('brand_ambassador');
		$columnArray = array (
			"fname",
			"lname",
			"certified",
			"level",
			"country",
			"phone",
			"email",
			"website",
			"facebook",
			"instagram",
			"twitter",
			"snapchat",
			"other_social",
			"week",
			"facity_name",
			"facility_website",
			"weekly",
			"learn",
			"bio",
			"ip_address",
			"ip_location",
			"date_time");
		$this->db->select('*');
		$this->db->from('brand_ambassador');
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'date_time like "%'.date('Y-m-d h:i:s', strtotime($data['search']['value'])).'%"'
			);
			$this->db->or_where(
				'fname like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'lname like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'certified like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'level like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'country like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'phone like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facebook like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'instagram like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'twitter like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'snapchat like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'other_social like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'week like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facity_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facility_website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'weekly like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'learn like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'bio like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_address like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_location like "%'.$data['search']['value'].'%"'
			);
		}
		$this->db->limit($data['length'], $data['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function getBrandAmbassadorInquirisCount($data = array())
	{
		$query = $this->db->get('brand_ambassador');
		$this->db->select('COUNT(*) AS aggregate');
		$this->db->from('brand_ambassador');		
		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'date_time like "%'.date('Y-m-d h:i:s', strtotime($data['search']['value'])).'%"'
			);
			$this->db->or_where(
				'fname like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'lname like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'certified like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'level like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'country like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'phone like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'email like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facebook like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'instagram like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'twitter like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'snapchat like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'other_social like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'week like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facity_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'facility_website like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'weekly like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'learn like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'bio like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_address like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'ip_location like "%'.$data['search']['value'].'%"'
			);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	function getBrandAmbassadorById($id) {
		$this->db->select('*');
		$this->db->from('brand_ambassador');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function deletebrandambassadorinquiry($id)
	{
		$this->db->delete('brand_ambassador', array('id' => $id));
		return true;
	}

	function getGoalData($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$this->db->where('status','A');
		$this->db->where("yes_no !=",'');
		$this->db->order_by('date','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getGoalResponceRate($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		$totalMailSent = count($data);
		$j = 0;
		foreach($data as $responceRate) {
			if($responceRate['yes_no'] != '' && $responceRate['status'] == 'A') {
				$count = $j+1;
				$j++;
			}
		}
		$TotalReponceRate = (100*$j)/$totalMailSent;
		return $TotalReponceRate;
	}

	function getGoalResponceChartYes($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		$totalMailSent = count($data);
		$j = 0;
		foreach($data as $responceRate) {
			if($responceRate['yes_no'] == 'yes' && $responceRate['status'] == 'A') {
				$count = $j+1;
				$j++;
			}
		}
		$TotalReponceRate = (100*$j)/$totalMailSent;
		return $TotalReponceRate;
	}

	function getGoalResponceChartNo($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		$totalMailSent = count($data);
		$j = 0;
		foreach($data as $responceRate) {
			if($responceRate['yes_no'] == 'no' && $responceRate['status'] == 'A') {
				$count = $j+1;
				$j++;
			}
		}
		$TotalReponceRate = (100*$j)/$totalMailSent;
		return $TotalReponceRate;
	}
	
	function getGoalResponceChartNoResponce($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);		
		$query = $this->db->get();		
		$data = $query->result_array();
		$totalMailSent = count($data);
		$j = 0;
		foreach($data as $responceRate) {
			if($responceRate['yes_no'] == '' && $responceRate['status'] == 'A') {
				$count = $j+1;
				$j++;
			}
		}
		$TotalReponceRate = (100*$j)/$totalMailSent;
		return $TotalReponceRate;
	}
	
	function getGoalResponceChart($id) {
		$this->db->select('*');
		$this->db->from('journal_goal_count');
		$this->db->where('user_id',$id);		
		$this->db->where('status','A');
		$query = $this->db->get();		
		$data = $query->result_array();		
		return $data;
	}

	function addGoalReview($data)
	{
		$this->db->insert('goal_review', $data);
		return true;
	}

	function getAllJournalDataById($id) {
		$this->db->select('*');
		$this->db->from('journal');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getGoalreviewData($id) {
		$this->db->select('*');
		$this->db->from('goal_review');
		$this->db->where('goal_id',$id);
		$this->db->group_by('goal_id');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function GetLoginAndLogoutINF($userName) {
		$this->db->select('*, datetime as date_time');
		$this->db->from('user_log');
		$this->db->where('username',$userName);
		$this->db->order_by('datetime','ASC');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function GetMessageLogDataForINF($email) {
		$this->db->select('*');
		$this->db->from('message_log');
		$this->db->where('email_address',$email);
		$this->db->where('origin',"Forgot Password Mail");
		$this->db->order_by('date_time','ASC');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function GetNewsletterDataForINF($email) {
		$this->db->select('*');
		$this->db->from('subscrib');
		$this->db->where('email',$email);
		$this->db->order_by('date_time','ASC');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	//Limit login fail.
	function AddLoginFailData($data) {
		$this->db->insert('login_limit',$data);
		return true;
	}
	
	function deleteLoginfaildata($ip) {
		$this->db->delete('login_limit', array('ip' => $ip));
		return true;
	}

	function getCurrentIPLoginFailCount($ip) {
		date_default_timezone_set(ini_get('date.timezone'));
		$this->db->select('*');
		$this->db->from('login_limit');
		$this->db->where('ip',$ip);
		$this->db->where('DATE(date_time)',date('Y-m-d'));
		$this->db->order_by('id','desc');
		$this->db->limit(5);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getRegisterEmailIdForFacebook($email) {
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('facebook_mail',$email);
		$this->db->where('login_type',"Facebook");
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getUserConfirmationCode($confirm,$id) {
		$this->db->select('confirm_code');
		$this->db->from('users');
		$this->db->where('confirm_code',$confirm);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function insertWizardActionLog($data) {
		$this->db->insert("action_log",$data);
		return true;
	}

	function insertUserActivityLog($data) {
		$this->db->insert("activity_log",$data);
		return true;
	}

	function getWWizardActionLogData($id) {
		$this->db->select('*');
		$this->db->from('action_log');
		$this->db->where('user_id',$id);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getUserActivityData($id) {
		$this->db->select('*');
		$this->db->from('activity_log');
		$this->db->where('user_id',$id);
		$this->db->order_by('date_time','ASC');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	/*Landing Data function start*/
	function addLandingData($data)
	{
		$this->db->insert('landing', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function GetAllLandingData() {
		$this->db->select('*');
		$this->db->from('landing');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	function GetAllLandingPaginationData($data = array())
	{
		$query = $this->db->get('landing');
		$columnArray = array (
			"id",
			"landing_page_name",
			"slug",
			"sub_title",
			"date_time");

        $this->db->select('*');
		$this->db->from('landing');

		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'landing_page_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'slug like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'sub_title like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'date_time like "%'.$data['search']['value'].'%"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getLandingAllCount()
	{
		$query = $this->db->get('landing');
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'landing_page_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'slug like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'sub_title like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'date_time like "%'.$data['search']['value'].'%"'
			);
        }
        $this->db->from('landing');
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function deleteLanding($id) {
		$this->db->delete('landing', array('id' => $id));
		$this->db->delete('landing_thankyou_log_2', array('landing_id' => $id));
		$this->db->delete('landing_log_1', array('landing_id' => $id));
		$this->db->delete('landing_log_2', array('landing_id' => $id));
		$this->db->delete('landing_popup_data', array('landing_id' => $id));
		$this->db->delete('landing_popup_log_2', array('landing_id' => $id));
		$this->db->delete('landing_thankyou', array('landing_id' => $id));
		$this->db->delete('landing_coupon', array('landing_id' => $id));
		$this->db->delete('landing_email_template', array('landing_id' => $id));
		return true;
	}

	function GetLandingDataById($id) {
		$this->db->select('*');
		$this->db->from('landing');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	function UpdateLanding($data,$id) {
		$this->db->where('id',$id);		
		$this->db->update('landing', $data);
		return true;
	}
	
	function checkUniqueSlugEditLading($slug, $id){
		$query = 'SELECT * FROM `landing` WHERE `id`!="'.$id.'" AND `slug`="'.$slug.'"';
		$result = $this->db->query($query);
		$data = $result->num_rows();
		if($data=="0") {
			return true;
		} else {
			return false;
		}
	}
	
	function checkUniqueSlugAddLading($slug){
		$query = 'SELECT * FROM `landing` WHERE `slug`="'.$slug.'"';
		$result = $this->db->query($query);
		$data = $result->num_rows();
		if($data=="0") {
			return true;
		} else {
			return false;
		}
	}
	
	function GetAllLandingTemplateData($id,$data = array())
	{
		$this->db->where('landing_id',$id);
		$query = $this->db->get('landing_email_template');
		$columnArray = array (
			"id",
			"template_list_name");

        $this->db->select('*');
		$this->db->from('landing_email_template');
		$this->db->where('landing_id',$id);

		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'template_list_name like "%'.$data['search']['value'].'%"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getLandingEmailAllCount($id)
	{
		$this->db->where('landing_id',$id);
		$query = $this->db->get('landing_email_template');
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'template_list_name like "%'.$data['search']['value'].'%"'
			);
        }
        $this->db->from('landing_email_template');
        $this->db->where('landing_id',$id);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function landingTemplateDelete($id) {
		$this->db->delete('landing_email_template', array('id' => $id));
		return true;
	}
	
	function insertLandingTemplateData($data) {
		$this->db->insert('landing_email_template',$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	function updateLandingTemplateData($data,$id) {
		$this->db->where('id',$id);
		$this->db->update('landing_email_template', $data);
		return true;
	}
	
	function GetLandingTemplateDataById($id) {
		$this->db->select('*');
		$this->db->from('landing_email_template');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function updateLandingCouponData($data,$id) {
		$this->db->where('id',$id);
		$this->db->update('landing_coupon', $data);
		return true;
	}

	function GetLandingCouponDataById($id) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('id',$id);
		$this->db->group_by('landing_id');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingAllCouponDataById($landing,$cid) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$landing);
		$this->db->where('coupon_list_id',$cid);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function GetAllLandingCouponData($id) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$id);
		$this->db->group_by('coupon_list_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function insertLandingCouponData($data) {
		$this->db->insert('landing_coupon',$data);		
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function landingCouponDelete($id) {
		$this->db->delete('landing_coupon', array('id' => $id));
		return true;
	}

	function landingCouponCodeDelete($coupon,$landing,$cid) {
		$this->db->where('coupon_code',$coupon);
		$this->db->where('landing_id',$landing);
		$this->db->where('coupon_list_id',$cid);
		$this->db->delete('landing_coupon');		
		return true;
	}

	function landingCouponAllDataDelete($coupon) {
		$this->db->delete('landing_coupon', array('landing_id' => $coupon));
		return true;
	}

	function landingCouponListDataDelete($coupon,$cid) {
		$this->db->delete('landing_coupon', array('landing_id' => $coupon,'coupon_list_id' => $cid));
		return true;
	}

	function GetLandingCouponStatusBycouponCode($coupon) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('coupon_code',$coupon);
		$this->db->where('status','1');
		$query = $this->db->get();		
		if($query->num_rows() == 1)
		{
			return '1';
		 
		} 
		else
		{
			return '0';
		}		
	}

	function GetLandingCouponAlready($coupon) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('coupon_code',$coupon);
		$query = $this->db->get();		
		$result = $query->row_array();
		return $result;
	}

	function deleteLandingThankyou($id) {
		$this->db->delete('landing_thankyou', array('id' => $id));
		return true;
	}	
	
	function GetAllLandingThanksData($id,$data = array())
	{
		$query = $this->db->get('landing_thankyou');
		$columnArray = array (
			"id",
			"landing_name",
			"title");

        $this->db->select('*');
		$this->db->from('landing_thankyou');
		$this->db->where('landing_id',$id); 

		if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'landing_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'title like "%'.$data['search']['value'].'%"'
			);
		}

		if (isset($data['order'])) {
			$this->db->order_by(
				$columnArray[$data['order'][0]['column']],
				$data['order'][0]['dir']
			);
		}
		$this->db->limit($data['length'], $data['start']);
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function getLandingThanksAllCount($id)
	{
		$this->db->where('landing_id',$id);
		$query = $this->db->get('landing_thankyou');
        $this->db->select('COUNT(*) AS aggregate');
        if (isset($data['search']['value']) && $data['search']['value'] != '') {

			$this->db->or_where(
				'id',
				$data['search']['value']
			);
			$this->db->or_where(
				'landing_name like "%'.$data['search']['value'].'%"'
			);
			$this->db->or_where(
				'title like "%'.$data['search']['value'].'%"'
			);
        }
        $this->db->from('landing_thankyou');
        $this->db->where('landing_id',$id);     
        $query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function insertLandingThanksData($data) {
		$this->db->insert('landing_thankyou',$data);		
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function updateLandingThanksData($data,$id){
		$this->db->where('id',$id);		
		$this->db->update('landing_thankyou', $data);
		return true;
	}

	function GetLandingThanksDataById($id) {
		$this->db->select('*');
		$this->db->from('landing_thankyou');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	function GetLandingUserDataBySlug($slug) {
		$this->db->select('*');
		$this->db->from('landing');
		$this->db->where('slug',$slug);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	function insertLandingPopupUserData($data) {
		$this->db->insert('landing_popup_data',$data);
		return true;
	}
	
	function GetLandingTemplateDataByLandingId($id) {
		$this->db->select('*');
		$this->db->from('landing_email_template');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function getRadomCouponCodeForLanding($lId,$Tid) {
		$this->db->select('coupon_code');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$lId);
		$this->db->where('template_id',$Tid);
		$this->db->where('status','0');		
		$this->db->order_by('RAND()');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function updateCouponCodeStatus($data,$lId,$coupon) {
		$this->db->where('coupon_code',$coupon);
		$this->db->where('landing_id',$lId);
		$this->db->update('landing_coupon', $data);		
		return true;
	}
	
	function getLandingStoreData($email,$lId) {
		$this->db->select('*');
		$this->db->from('landing_popup_data');
		$this->db->where('email',$email);
		$this->db->where('landing_id',$lId);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function getLandingIdBySlug($slug) {
		$this->db->select('id');
		$this->db->from('landing');
		$this->db->where('slug',$slug);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingthankyoudata($id) {
		$this->db->select('*');
		$this->db->from('landing_thankyou');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function InsertLandingLog_1($data) {
		$this->db->insert("landing_log_1",$data);
		return true;
	}

	function InsertLandingLog_2($data) {
		$this->db->insert("landing_log_2",$data);
		return true;
	}

	function UpdateLandingLog_2($id,$data) {
		$this->db->where('id',$id);
		$this->db->update("landing_log_2",$data);
		return true;
	}

	function InsertLandingPopupLog_2($data) {
		$this->db->insert("landing_popup_log_2",$data);		
		return true;
	}

	function UpdateLandingPopupLog_2($id,$data) {
		$this->db->where('id',$id);
		$this->db->update("landing_popup_log_2",$data);		
		return true;
	}

	function getLandingLog2DataByIp($id,$ip) {
		$this->db->select('*');
		$this->db->from('landing_log_2');
		$this->db->where('landing_id',$id);
		$this->db->where('ip',$ip);
		$this->db->where('out_time','');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function getLandingPopupLog2DataByIp($id,$ip) {
		$this->db->select('*');
		$this->db->from('landing_popup_log_2');
		$this->db->where('landing_id',$id);
		$this->db->where('ip',$ip);
		$this->db->where('out_time','');
		$query = $this->db->get();		
		$result = $query->row_array();
		return $result;
	}

	function InsertLandingThankyouLog_2($data) {
		$this->db->insert("landing_thankyou_log_2",$data);
		return true;
	}

	function getLandingThankyouLog2DataByIp($id,$ip) {
		$this->db->select('*');
		$this->db->from('landing_thankyou_log_2');
		$this->db->where('landing_id',$id);
		$this->db->where('ip_address',$ip);
		$this->db->where('out_time','');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	function getLandingThankyouLog2DataByIpAndTime($id,$ip) {
		$this->db->select('*');
		$this->db->from('landing_thankyou_log_2');
		$this->db->where('landing_id',$id);
		$this->db->where('ip_address',$ip);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function UpdateLandingThanksLog_2($data,$id) {
		$this->db->where('id',$id);
		$this->db->update("landing_thankyou_log_2",$data);
		return true;
	}

	function getLandingLog1Data($id) {
		$this->db->select('*');
		$this->db->from('landing_log_1');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getLandingLog2Data($id) {
		$this->db->select('*,sum(visit_page) as Total_visiter, sum(button_click) as Total_button_clicks');
		$this->db->from('landing_log_2');
		$this->db->where('country !=', 'IN');
		$this->db->where('country !=', 'ID');
		$this->db->where('landing_id', $id);
		$this->db->group_by('landing_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}	

	function getLandingPopupLog2Data($id) {
		$this->db->select('*,sum(opens) as Total_opens, sum(button_click) as Total_button_clicks');
		$this->db->from('landing_popup_log_2');
		$this->db->where('country !=', 'IN');
		$this->db->where('country !=', 'ID');
		$this->db->where('landing_id', $id);
		$this->db->group_by('landing_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	function getLandingThankyouLog2Data($id) {
		$this->db->select('*,sum(page_visit) as Total_visiter');
		$this->db->from('landing_thankyou_log_2');
		$this->db->where('country !=', 'IN');
		$this->db->where('country !=', 'ID');
		$this->db->where('landing_id', $id);
		$this->db->group_by('landing_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getLandingLogViewDataByid($id) {
		$this->db->select('*');
		$this->db->from('landing_log_2');
		$this->db->where('landing_id',$id);		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	function getLandingPopupLogViewDataByid($id) {
		$this->db->select('*');
		$this->db->from('landing_popup_log_2');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getLandingThankyouLogViewDataByid($id) {
		$this->db->select('*');
		$this->db->from('landing_thankyou_log_2');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getCountForSubmissionForLanding($id) {
		$this->db->select('count(*) as totalCount');
		$this->db->from('landing_popup_data');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getLandingSubmissionsData($id) {
		$this->db->select('*');
		$this->db->from('landing_popup_data');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function GetLandingUsedCouponCode($id,$cid) {
		$this->db->select('count(coupon_code) as couponUsed');
		$this->db->from('landing_coupon');
		$this->db->where('status','1');
		$this->db->where('landing_id',$id);
		$this->db->where('coupon_list_id',$cid);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingNOtUsedCouponCode($id,$cid) {
		$this->db->select('count(coupon_code) as couponNotUsed');
		$this->db->from('landing_coupon');
		$this->db->where('status','0');
		$this->db->where('landing_id',$id);
		$this->db->where('coupon_list_id',$cid);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function addTempLandingData($data)
	{
		$this->db->insert('temp_landing', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function GetLandingTempDataByid($id) {
		$this->db->select('*');
		$this->db->from('temp_landing');
		$this->db->where('id',$id);
		$query = $this->db->get();		
		$result = $query->row_array();
		return $result;
	}
	
	function inserTempLandingThanksData($data) {
		$this->db->insert('temp_landing_thankyou',$data);		
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function GetTempLandingthankyoudata($id) {
		$this->db->select('*');
		$this->db->from('temp_landing_thankyou');
		$this->db->where('id',$id);
		$query = $this->db->get();		
		$result = $query->row_array();
		return $result;
	}
	
	function getLandingSidebar() {
		$this->db->select('*');
		$this->db->from('landing');
		$this->db->where('sidebar_archive','0');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getLandingArchiveSidebar() {
		$this->db->select('*');
		$this->db->from('landing');
		$this->db->where('sidebar_archive','1');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function GetLandingCouponDataByLandingId($id) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingEmailDataByLandingId($id) {
		$this->db->select('*');
		$this->db->from('landing_email_template');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingThankDataByLandingId($id) {
		$this->db->select('*');
		$this->db->from('landing_thankyou');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function getMaxCouponListName($id) {
		$this->db->select('max(`coupon_list_id`) as CouponListId');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$id);		
		$query = $this->db->get();		
		$result = $query->row_array();
		return $result;
	}

	function getMaxEmailListName($id) {
		$this->db->select('max(`template_list_id`) as EmailListId');
		$this->db->from('landing_email_template');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	function GetLandingAllTemplateDataByLandingId($id) {
		$this->db->select('*');
		$this->db->from('landing_email_template');
		$this->db->where('landing_id',$id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function GetLandingCouponAutomaticChange($Lid,$Tid) {
		$this->db->select('*');
		$this->db->from('landing_coupon');
		$this->db->where('landing_id',$Lid);
		$this->db->where_not_in('template_id', $Tid);
		$this->db->group_by('template_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	/*Landing Data function end*/	

	function getReferralEmailData() {
		$this->db->select('*');
		$this->db->from('referral_email');
		$this->db->where('id','1');
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function InsertReferraldata($data) {
		$this->db->where('id','1');
		$this->db->update("referral_email",$data);
		return true;		
	}
	
	function getcommunityUserName($id) {
		$this->db->select('*');
		$this->db->from('promotionsubscribers');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function UpdateCommunityUserNameData($data,$id) {
		$this->db->where('id',$id);
		$this->db->update("promotionsubscribers",$data);
		return true;		
	}
	
	function getLandingSubmissionUserName($id) {
		$this->db->select('*');
		$this->db->from('landing_popup_data');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function UpdateLandingUserNameData($data,$id) {
		$this->db->where('id',$id);
		$this->db->update("landing_popup_data",$data);
		return true;		
	}
	
	function getExpertsData() {
		$this->db->select('*');
		$this->db->from('expert');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function getExpertsDataById($id) {
		$this->db->select('*');
		$this->db->from('expert');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function updateExperts($data,$id) {
		$this->db->where('id',$id);
		$this->db->update('expert',$data);
		return true;
	}
	
	function add_register_product($data) {
		$this->db->insert('register_product_add',$data);
		return true;
	}

	function getProductByProductName($name) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('productName',$name);
		$query = $this->db->get();
		return $query->row_array();
	}

	function getRegisterProductByUserId($id) {
		$this->db->select('*');
		$this->db->from('register_product_add');
		$this->db->where('user_id',$id);
		$this->db->group_by('product_id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function getRegisterProductByUserIdAndLastId($id) {
		$this->db->select('*');
		$this->db->from('register_product_add');
		$this->db->where('user_id',$id);
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
	}

	function addProductQuestion($data) {
		$this->db->insert('product_question',$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	function addProductAnswer($data) {
		$this->db->insert('	product_answer',$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function GetQuestionData($id) {
		$this->db->select('*');
		$this->db->from('product_question');
		$this->db->where('product_id',$id);
		$this->db->where('status',1);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function getMenuProductByCategory($category) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('productCategories',$category);
		$this->db->where('product_status','0');
		$this->db->order_by('order_product');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getMenuProductBySubCategory($category,$subId) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('product_Sub_Cate', $subId);
		$this->db->like('productCategories',$category);		
		$this->db->where('product_status','0');
		$this->db->order_by('order_product');
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function getMenuProductBySubSubCategory($Subcategory,$subsId) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('product_Sub_Sub_Cate', $subsId);
		$this->db->like('product_Sub_Cate',$Subcategory);
		$this->db->where('product_status','0');
		$this->db->order_by('order_product');
		$query = $this->db->get();		
		$data = $query->result_array();
		return $data;
	}

	function getProductBySubCategory($category) {
		$this->db->select('*');
		$this->db->from('product');		
		$this->db->like('product_Sub_Cate',$category);
		$this->db->where('product_status','0');
		$this->db->order_by('order_product');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getProductBySubsCategory($category) {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->like('product_Sub_Sub_Cate',$category);
		$this->db->where('product_status','0');
		$this->db->order_by('order_product');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	/*Landing page end time*/

	function getLandingendtimeBySlug($slug) {
		date_default_timezone_set('America/Los_Angeles');
		$this->db->select('*');
		$this->db->from('landing');
		$this->db->where('slug',$slug);
		$this->db->where('landing_end_time >=', date('Y-m-d H:i:s'));
		$this->db->where('archive','0');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function archiveLanding($id,$data) {
		$this->db->where('id',$id);
		$this->db->update('landing',$data);
		return true;
	}

	function archiveLandingSidebar($data) {
		$this->db->where('archive',1);
		$this->db->update('landing',$data);
		return true;
	}
	
	/* Include user product */
	
	function getUserCheckProduct() {
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('user_product','1');
		$this->db->order_by('order_product');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getUserCheckProductData() {
		$this->db->select('product.*,exercise.*');
		$this->db->from('product');
		$this->db->join('exercise', 'product.productName = exercise.product');
		$this->db->where('product.user_product','1');
		$this->db->where('exercise.status', '1');
		$this->db->group_by('exercise.product');
		$this->db->order_by('product.id','desc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function getRegisterInsAndExe($uID) {
		$this->db->select('*');
		$this->db->from('register_product_add');
		$this->db->where('user_id',$uID);
		$this->db->group_by('product_id');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function userHomeDisplayWelcome($Name) {
		$this->db->select('count(id) as totalLogin');
		$this->db->from('user_log');
		$this->db->where('action','Successful Login');
		$this->db->where('username',$Name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	function updateBlacklist($data)
	{
		$this->db->where('id', '1');
		$this->db->update('block_ip', $data);
		return true;
	}

	function getSlugBlacklistData() {
		$this->db->select('*');
		$this->db->from('block_ip');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function deleteNotfoundPage($id)
	{
		$this->db->delete('notfound_data', array('id' => $id)); 
		return true;
	}

	function addBlackIp($data) {
		$this->db->insert('blacklist_ip',$data);
		return true;
	}

	function getAllBlackListIp($ip) {
		$this->db->select('*');
		$this->db->from('blacklist_ip');
		$this->db->where('ip',$ip);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function getBlockIpList(){
		$this->db->select('*');
		$this->db->from('blacklist_ip');
		$this->db->group_by('ip');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function deleteBlocklistIP($ip) {
		$this->db->delete('blacklist_ip', array('ip' => $ip));
		return true;
	}

	function getAllProductCategoriesByParentCat($parent) {
		$this->db->select('*');
		$this->db->where('parent_id',$parent);
		$this->db->where('sub_id',0);
		$this->db->where('category_status','0');
		$query = $this->db->get('productCategories');		
		$data = $query->result_array();
		return $data;
	}

	function getAllProductCategoriesByParentCatAndSubCat($parent,$sub) {
		$this->db->select('*');
		$this->db->where('parent_id',$parent);
		$this->db->where('sub_id',$sub);
		$this->db->where('category_status','0');
		$query = $this->db->get('productCategories');
		$data = $query->result_array();
		return $data;
	}

	function getProductCategoriesIdByName($name) {
		$dj_genres = trim($name, "'");
		$dj_genres_array = explode(",", $dj_genres);
		$this->db->select('GROUP_CONCAT(id) as CategoryId');
		$this->db->where_in('category',$dj_genres_array);
		$this->db->where('category_status','0');
		$query = $this->db->get('productCategories');
		$data = $query->row_array();
		return $data;
	}

}
?>
