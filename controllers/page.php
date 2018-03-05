<?php if ( ! defined('BASEPATH')) exit('Higher security clearance level required');

class Page extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('layout');          // Load layout library
		$this->load->model('oi','',TRUE);
		$this->load->model('user','',TRUE);
    }

	public function programs()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Programs');
		$this->layout->description('Programs');

    		$data['chdatas'] = $this->oi->getAllChangeGym();
		$data['rpdatas'] = $this->oi->getAllResidencyProgrambyDate();
		$data['tbcrpdatas'] = $this->oi->getAllResidencyProgrambyTbc();
		$data['programstarts'] = "2015/07/25";
		$this->layout->view('page/programs', $data);
	}
	
	public function fellowship()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Fellowship');
		$this->layout->description('Fellowship');
		$data = array();
		$this->layout->view('page/fellowship', $data);
	}

	public function summit()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Summit');
		$this->layout->description('Summit');    
    
    $data['content'] = $this->oi->getSummitPageContent();		
		$this->layout->view('page/summit', $data);
	}

	public function about_us()
	{
		
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Aboutus');
		$this->layout->description('Aboutus');
		$data = array();
		$this->layout->view('page/about-us', $data);
	}
	public function comments_add(){

		$this->load->model('blog','',TRUE);
			
			
		 if($this->input->post('name')){					
			$data = array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'comment' => $this->input->post('comments'),
			'blog_id' => $this->input->post('blog_id'),
			'user_id' => $this->session->userdata['logged_in']['id']
			);
			
			$this->blog->addComments($data);
			
			$blogtitle = $this->input->post('blogtitle');
			$commentername = $this->input->post('name');
			$commenteremail = $this->input->post('email');
			$comment = $this->input->post('comments');
			$blogpodtlink = base_url().'blog/'.$this->input->post('blogslug');
			$adminreviewlink = base_url().'admin/blog_comment_view/'.$this->input->post('blogid');
			
			
			$to = $this->global_admin_email; 
			$subject = "[BLOG COMMENT] added > pending review";

			$message = "
				<html>
					<head>
					</head>
				<body>
					<table>
						<tr>
							<td><strong>Title of Blogpost:</strong></td>
							<td><a href=".$blogpodtlink.">".$blogtitle."</a></td>
						</tr>
						
						<tr>
							<td><strong>Name of commenter:</strong></td>
							<td>".$commentername."</td>
						</tr>

						<tr>					
							<td><strong>Email of commenter:</strong></td>
							<td>".$commenteremail."</td>
						</tr>
						
						<tr>
							<td><strong>Review link:</strong></td>
							<td>Click<a href=".$adminreviewlink."> here</a> approve/decline</td>
						</tr>
						
						<tr>
							<td><strong>Comment:</strong></td>
							<td>".$comment."</td>
						</tr>
					</table>
				</body>
			</html>
			";
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
			$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
			mail($to,$subject,$message,$headers);			
			
			$this->session->set_flashdata("message","Your comment was added successfully and will be publicly visible upon admin review.");
		}else{
			$this->session->set_flashdata("message","Something is Wrong.");
		}
			
			$blogdata = $this->blog->getBlogById($this->input->post('blog_id'));
			redirect('blog/'.$blogdata['slug']);		
	 }
	 
	 
	 public function replyoncomment($cid,$bid){
		 
		 
		 $this->load->model('blog','',TRUE);
		 $userdata = $this->user->getUserById($this->session->userdata['logged_in']['id']);
		 $comments = $_POST['comment'];
		 	$data = array(			
				'name' => $userdata['username'],
				'email' => $userdata['email'],
				'comment' => $comments,
				'blog_id' => $bid,			
				'parent_id' => $cid,
				'user_id' => $this->session->userdata['logged_in']['id']
			);			
			
			$this->blog->addComments($data);exit;			
	 }	 


	public function aycrg_form(){
		$this->load->model('formsubmition','',TRUE);
		if($this->input->post())
		{
			$ip_address = $this->session->userdata('ip_address');
			$data = $_POST;
			$new_date = date('Y-m-d',strtotime($_POST['dob']));
			$data['dob'] = $new_date;
			$data['ip_address'] = $ip_address;
			$data['country_code'] = $this->locationofuser($ip_address);
			
			$last_id = $this->user->addResidencyApplicationForm($data);
			$adminemail = $this->formsubmition->adminemailforresidencyform();
			$to = $adminemail['email'];
			$subject = "[RESIDENCY-APPLICATION] ".$data['email_service'];
			
			$message = "
			<html>
				<head>
					<title>Residency Program Application Form Submission</title>
				</head>
				<body>
					<p>Residency Program Application Form Submission with below details.</p>
					<table>

						<tr>
							<td><strong>Firstname:</strong></td>
							<td>".$data['firstname']."</td>
						</tr>
						
						<tr>
							<td><strong>Lirstname:</strong></td>
							<td>".$data['lastname']."</td>
						</tr>

						<tr>					
							<td><strong>Email:</strong></td>
							<td>".$data['email_service']."</td>
						</tr>
						
						<tr>
							<td><strong>Contact Number:</strong></td>
							<td>".$data['contactnumber']."</td>
						</tr>
						
						<tr>
							<td><strong>Skype ID:</strong></td>
							<td>".$data['skypeid']."</td>
						</tr>
						
						<tr>
							<td><strong>Date Of Birth:</strong></td>
							<td>".$data['dob']."</td>
						</tr>
						
						<tr>
							<td><strong>little bit about yourself:</strong></td>
							<td>".nl2br($data['aboutyourself'])."</td>
						</tr>
						
						<tr>
							<td><strong>Accommodation:</strong></td>
							<td>".$data['accommodation']."</td>
						</tr>
						
						<tr>
							<td><strong>How did you hear about us?</strong></td>
							<td>".$data['hearaboutus']."</td>
						</tr>
						
						<tr>
							<td><strong>PROMO CODE:</strong></td>
							<td>".$data['promocode']."</td>
						</tr>
					
					</table>
				</body>
			</html>
		";
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
		$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
		mail($to,$subject,$message,$headers);
			echo "successfull";exit;
		}
		else{
			echo "fail";exit;
		}
		
	}
	
	public function search(){
		
		$addSearchData = array(
				'searchterm' => $this->input->post('search'),						
				'ip' => $this->session->userdata('ip_address'),
				'country' => $this->getLocationInfoByIp(),
				'results' => '0'
		);
			
		$this->oi->addMainSearch($addSearchData);		
	}

	public function work_with_us()
	{

		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Workwithus');
		if($_POST['email']){
			$to = $this->global_admin_email;
			$subject = "[WORKWITHUS] ".$_POST['fname'];
			$message = "
			<html>
				<head>
				</head>
				<body>
					<p>/work-with-us form has been submitted.</p>
					<table>
						<tr>
							<td><strong>Name</strong></td>
							<td>".$_POST['fname']."</td>
						</tr>
						<tr>
							<td><strong>Email</strong></td>
							<td>".$_POST['email']."</td>
						</tr>
						<tr>
							<td><strong>Message</strong></td>
							<td>".nl2br($_POST['message'])."</td>
						</tr>
					</table>
				</body>
			</html>
			";
			// Always set content-type when sending HTML email

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// More headers
			$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
			$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
			mail($to,$subject,$message,$headers);
			$this->session->set_flashdata("message","<font class='success'>Thanks For your interest, we will get back to you soon.</font>");
            redirect('work-with-us');

		}



		$data = array();

		$this->layout->view('page/work-with-us', $data);
	}	
	public function other_schools_search()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Life Schools Search Result');
		$this->layout->description('Life Schools Search Result');
		$data = array();


			//echo "<pre>";print_r($this->input->post());exit;
			$searchdata['keyword'] = $this->input->post('keyword');
			$searchdata['stream'] = $this->input->post('stream');
			$searchdata['location'] = $this->input->post('location');
			$searchdata['pricerange'] = $this->input->post('pricerange');
			$searchdata['duration'] = $this->input->post('duration');

			$searchresult = $this->oi->searchOi($searchdata);
			//echo "<pre>";print_r($searchresult);exit;
			foreach($searchresult as $key =>$value)
			{
				$searchresult[$key]['oistream'] = $this->oi->getHighestRatedAof($searchresult[$key]['id']);		
			}
			//exit;
			//echo "<pre>";print_r($searchresult);exit;
			$data['oi']  = $searchresult;

			if($this->input->post('keyword')!="")
				$search['searchterm'][] = $this->input->post('keyword');

			if($this->input->post('stream')!="")
				$search['searchterm'][] = $this->input->post('stream');

			if($this->input->post('location')!="")
				$search['searchterm'][] = $this->input->post('location');

			if($this->input->post('pricerange')!="")
				$search['searchterm'][] = $this->input->post('pricerange');

			if($this->input->post('duration')!="")
				$search['searchterm'][] = $this->input->post('duration');

			$data['searchterm'] = implode(',',$search['searchterm']);
			$totaloi = $this->oi->getAllOi();
			$data['totaloi'] = count($totaloi);
			if($searchdata['stream'] != '')
			{
				$data['searchforstream'] = $searchdata['stream'];
			}
			else
			{
				$data['searchforstream'] = '';
			}

			 $keyword_serch_term = ($this->input->post('keyword')=="") ? 'default' : $this->input->post('keyword');
			 $stream_serch_term = ($this->input->post('stream')=="") ? 'default' : $this->input->post('stream');
			 $location_serch_term = ($this->input->post('location')=="") ? 'default' : $this->input->post('location');
			 $pricerange_serch_term = ($this->input->post('pricerange')=="") ? 'default' : $this->input->post('pricerange');
			 $duration_serch_term = ($this->input->post('duration')=="") ? 'default' : $this->input->post('duration');
			$addSearchData = array(
			'searchterm' => $keyword_serch_term,
			'stream'	=>	$stream_serch_term,
			'location'	=>	$location_serch_term,
			'pricerange'=>	$pricerange_serch_term,
			'duration'	=>	$duration_serch_term,
			'ip' => $this->session->userdata('ip_address'),
			'country' => $this->getLocationInfoByIp(),
			'results' => count($data['oi'])
			);
			//echo "<pre>";print_r($addSearchData);exit;
			$this->oi->addOiSearch($addSearchData);
		
		//echo "<pre>";print_r($data['oi']);exit;
		$this->layout->view('page/other-schools-search', $data);
	}
	public function other_schools()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Life Schools');
		$this->layout->description('Life Schools');
		$data = array();
		$data['stream'] = $this->oi->getAllStream();
		$data['location'] = $this->oi->getAllLocation();
		$data['pricerange'] = $this->oi->getAllPricerange();
		$data['duration'] = $this->oi->getAllDuration();
		$data['slideroi'] = $this->oi->getSliderOi();		
    $data['sliderSchools'] = $this->oi->sliderSchools();		    
		$this->layout->view('page/other-schools', $data);
	}
	public function other_school($id)
	{
		
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Life School Program Page');
		$this->layout->description('Life Schools Program Page');
		$data = array();
		$data['oi'] = $this->oi->getOtherinsBySlug($id);
		$data['oi']['oistream'] = $this->oi->getHighestRatedAof($data['oi']['id']);
		$data['oi']['school'] = $this->oi->getSchoolData($data['oi']['school']);
		$data['oi']['tags'] = $this->oi->getAllOiTag($data['oi']['school']);
		$data['oiotherdates'] = $this->oi->getOiDates($data['oi']['id']);
		//echo "<pre>";print_r($data['oi']);exit;
		$this->layout->view('page/other-school-details', $data);
	}
  
  public function life_school_details($slug)
	{
		
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Life School Page');
		$this->layout->description('Life Schools Program Page');
		$data = array();
		$data['oi'] = $this->oi->getSchoolData($slug);		
		//~ echo "<pre>";print_r($data['oi']);exit;
		$this->layout->view('page/life-school-details', $data);
	}

	function getLocationInfoByIp(){

		$user_ip_address=$_SERVER['REMOTE_ADDR'];
		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$user_ip_address));
		if($ip_data && $ip_data->geoplugin_countryName != null){
		$user_country_code = $ip_data->geoplugin_countryCode;
		}
		else{
		$user_country_code="N/A";
		}
		return $user_country_code;
	}

	public function blog()
	{
		$this->load->library("pagination");
		$this->load->model('blog','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Blog');
		$this->layout->description('Blog');
		$data = array();

		$config = array();
		$config["base_url"] = base_url() . "blog/";
		$total_record = $this->blog->getAllActiveBlogs();
		$config["total_rows"] = count($total_record);
		$config["per_page"] = 6;        
		$config["uri_segment"] = 2; 
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		$allblog = $this->blog->getAllActiveBlogsPagination($config["per_page"],$page);
		$data["links"] = $this->pagination->create_links();
		foreach($allblog as $key =>$value)
		{

			// showing display name
			  if(is_numeric($allblog[$key]['author'])){	  
				  
				$author=$this->user->getUserById($allblog[$key]['author']);
				
				//if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
				if($author['displayname']!=""){
				  $allblog[$key]['author']=$author['displayname'];
				}else{
				$allblog[$key]['author']="";
				}
			  }
	
			$allblog[$key]['tagarray'] = array();
			if($value['tag'] != '')
			{
				$allblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
			}
        	$allblog[$key]['blog_image'] = $this->blog->getBlogImageById($allblog[$key]['id']);	
        	
        	$comment = $this->blog->getAllCommentsByBlog($allblog[$key]['id']);		
        	$allblog[$key]['commentcount']  = count($comment);	
		}
    
    $allpopularblog = $this->blog->getAllBlogByPolularity();
    
    foreach($allpopularblog as $key =>$value)
    {  
      $allpopularblog[$key]['tagarray'] = array();
      if($value['tag'] != '')
      {
        $allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
      }
      $allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		
      $popularComment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
      $allpopularblog[$key]['commentcount']  = count($popularComment);
    }
    
    $data['popular_list'] = $allpopularblog;
    $data['archive_list'] = $this->blog->blogArchive();
    $data['tag_list'] = $this->blog->getAllBlogTag();
    $data['category_list'] = $this->blog->getAllBlogCategory();
    
    $data['blog'] = $allblog;
    $this->layout->view('page/blog', $data);
	}
  
    
  public function blog_list_by($param){		    
        
    $this->load->model('blog','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Blog');
		$this->layout->description('Blog');
    $data = array();       
    
    $allpopularblog = $this->blog->getAllBlogByPolularity();    
    foreach($allpopularblog as $key =>$value)
    {  
      $allpopularblog[$key]['tagarray'] = array();
      if($value['tag'] != '')
      {
        $allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
      }
      $allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		
      $popularComment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
      $allpopularblog[$key]['commentcount']  = count($popularComment);
    }
    
    $data['list_by']=$param;
    $data['category_list'] = $this->blog->getAllBlogCategory();    		
    $data['popular_list'] = $allpopularblog;        
    $data['tag_list'] = $this->blog->getAllBlogTag();
    $data['archive_list'] = $this->blog->blogArchive();
    
    if($param === "tags"){
      $data['allList']=$data['tag_list'];      
    }
    else if($param === "categories"){
      $data['allList']=$data['category_list'];
    }
    else{
      redirect('error');
    }
    
    $this->layout->view('page/blog-list-by',$data);    
  }
  
  
	public function blogpost($slug){
			$this->load->model('blog','',TRUE);
			$data['blog'] = $this->blog->blogBySlug($slug);
			$data['blog']['tagarray'] = array();
			$data['blog']['tagarray'] = $this->blog->getBlogTag($data['blog']['tag']);
			$this->load->library('layout');          // Load layout library
			$this->layout->layout_view = 'layout/default.php';
			$this->layout->title($data['blog']['title']);
			$this->layout->description($data['blog']['title']);
			$this->layout->view('page/blogpost', $data);
	}

	public function in_the_news()
	{
		$this->load->model('news','',TRUE);
		$this->load->model('blog','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('In The News');
		$data = array();
		$allnews = $this->news->getAllActiveNews();
		foreach($allnews as $key =>$value)
		{
			$allnews[$key]['tagarray'] = array();
			if($value['tag'] != '')
			{
				$allnews[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
			}
		}
		$data['news'] = $allnews;
		$this->layout->view('page/in-the-news', $data);
	}
	public function read_news($slug){
			$this->load->model('news','',TRUE);
			$data['news'] = $this->news->newsBySlug($slug);
			$data['news']['tagarray'] = array();
			$data['news']['tagarray'] = $this->news->getNewsTag($data['news']['tag']);
			$this->load->library('layout');          // Load layout library
			$this->layout->layout_view = 'layout/default.php';
			$this->layout->title($data['news']['title']);
			$this->layout->description($data['news']['title']);
			$this->layout->view('page/read-news', $data);
	}
	public function read_blog($slug){	
			$this->load->library('layout');          // Load layout library
			$this->layout->layout_view = 'layout/default.php';
			$this->load->model('blog','',TRUE);
			$this->load->model('user','',TRUE);
			$blogdata = $this->blog->blogBySlug($slug);

			
			$userdata = $this->user->getUserById($this->session->userdata['logged_in']['id']);
			
			$this->blog->addBlogPopularity($slug);
			$blogdata['blog_image'] = $this->blog->getBlogImageById($blogdata['id']);
			
			$comment = $this->blog->getAllCommentsByBlog($blogdata['id']);		
      $blogdata['commentcount']  = count($comment);
        	
        	
			$data['blog'] = $blogdata;
            
      $author=$this->user->getUserById($data['blog']['author']);
      //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
      if($author['displayname']!=""){
        $data['blog']['author']=$author['displayname'];
      }
			//$data['blogcomment'] = $this->blog->getAllCommentsByBlog($blogdata['id']);
			
			$parentcomments = $this->blog->getAllParentBlog($blogdata['id']);
			
			for($i=0;$i<count($parentcomments);$i++){
						$parentcomments[$i]['first_child'] = $this->blog->getAllFirstChildComment($parentcomments[$i]['id']);
						for($s=0;$s<count($parentcomments[$i]['first_child']);$s++){
							$parentcomments[$i]['first_child'][$s]['second_child'] = $this->blog->getAllFirstChildComment($parentcomments[$i]['first_child'][$s]['id']);
							
							for($t=0;$t<count($parentcomments[$i]['first_child'][$s]['second_child']);$t++){
								$parentcomments[$i]['first_child'][$s]['second_child'][$t]['third_child'] = $this->blog->getAllFirstChildComment($parentcomments[$i]['first_child'][$s]['second_child'][$t]['id']);
							}	
						}	
			}		
			$data['blogcomment'] = $parentcomments;
		//	echo "<pre>";print_r($data['blogcomment']);exit;
			$this->layout->title($data['blog']['title']);
			$data['archive_list'] = $this->blog->blogArchive();
			$data['tag_list'] = $this->blog->getAllBlogTag();
			$data['category_list'] = $this->blog->getAllBlogCategory();
			 $allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{

		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		
        $popularComment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($popularComment);
			}
			$logged_in_or_not = ($this->session->userdata('logged_in')) ? "Logged In": "Not Logged In";
			$data['logged_in_or_not'] = $logged_in_or_not;
			$data['popular_list'] = $allpopularblog;
			$data['username'] = $userdata['username'];
			$data['useremail'] = $userdata['email'];
			$this->layout->view('page/read-blog', $data);
	}
	public function residency_programs()
	{
		redirect('/learning-retreats', 'refresh');
	}
	public function learning_retreats()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Learning Retreats');
		$this->layout->description('Learning Retreats');
		$data = array();
		$data['contents'] = $this->oi->getResidency_programs();
		$data['rrpdatas'] = $this->oi->getAllResidencyProgrambyDate();
		$data['rtbcrpdatas'] = $this->oi->getAllResidencyProgrambyTbc();
		$this->layout->view('page/programs-residency', $data);
	}
	
	public function corporate_programs()
	{
		redirect('/team-programs', 'refresh');
	}
	public function team_programs()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Team Programs');
		$this->layout->description('Team Programs');
		$data = array();
    $data['contents']=$this->oi->getCorporate_programs();
		$this->layout->view('page/programs-corporate', $data);
	}
	public function change_gyms()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Change Gym');
		$this->layout->description('Change Gym');
		$data['datas'] = $this->oi->getAllChangeGym();
		$this->layout->view('page/programs-changegym', $data);
	}
	public function our_people()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Our People');
		$this->layout->description('Our People');
		$data = array();
		$this->layout->view('page/our-people', $data);
	}
	public function newsletter(){

			$ip = $this->session->userdata('ip_address');
			$country = $this->locationofuser($ip);
			$sub = "[NEWSLETTER] ".$this->input->post('subemail');
			$this->load->model('newsletter','',TRUE);
			$data = array(
			'email' => $this->input->post('subemail')
			);
			
			$this->newsletter->addSubscriber($data);
			$to = $this->global_admin_email;
			$subject = $sub;

			$message = "".$this->input->post('subemail')."  sbuscribed to the newsletter (Footer form) from IP ".$ip." in ".$country."";
			
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
			$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
			mail($to,$subject,$message,$headers);
			exit;
	}

	public function submitform(){

		$this->load->model('formsubmition','',TRUE);
		$data = array(
		'email' => $this->input->post('email'),
		'content' => $this->input->post('content')
		);
		$this->formsubmition->addsubmitionform($data);
		$adminemail = $this->formsubmition->adminemail();
		$to = $adminemail['email'];
		$subject = "[NEWSLETTER] ".$data['email'];

		$message = "
			<html>
				<head>
					<title>New Newsletter Subscription</title>
				</head>
				<body>
					<p>Newsletter Subscription from website footer.</p>
					<table>

					<tr>
						<td><strong>Email:</strong></td>
						<td>".$data['email']."</td>
					</tr>

					<tr>
						<td><strong>Content:</strong></td>
						<td>".nl2br($data['content'])."</td>
					</tr>
				
				</table>
				</body>
			</html>
		";
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
		$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
		mail($to,$subject,$message,$headers);
		exit;
	}
	
	public function tckform(){


		$this->load->model('formsubmition','',TRUE);
		$ipaddress = $this->session->userdata('ip_address');
		$country_code = $this->locationofuser($ipaddress);
		$data = array(
		'email' => $this->input->post('tckemail'),
		'ip' => $ipaddress,
		'country'=>$country_code
		);
		$this->formsubmition->addtcsform($data);
		$adminemail = $this->formsubmition->adminemailfortckform();		
		$to = $adminemail['email'];
		$subject = "[TCKCONNECT] ".$data['email'];

		$message = "
			<html>
				<head>
					<title>TCK Connect Signup</title>
				</head>
				
				<body>
					<p>Yay! Another TCK Connect signup.</p>
					<table>

					<tr>
						<td><strong>Email:</td>
						<td>".$data['email']."</td>
					</tr>

					<tr>
						<td><strong>IP:</strong></td>
						<td>".$data['ip']."</td>
					</tr>

					<tr>
						<td><strong>Country:</strong></td>
						<td>".$data['country']."</td>
					</tr>
				
				</table>
				</body>
			</html>
		";
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <jatin.b.php@gmail.com>' . "\r\n";
		$headers .= "Bcc: jatin.b.php@gmail.com \r\n";
		mail($to,$subject,$message,$headers);
		exit;
	}
	
	public function all_blogs($category)
	{
		$category = urldecode($category);
		$this->load->model('blog','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title($category.' Category Blog');
		$this->layout->description($category.' Category Blog');
		$data = array();
		$data['blog'] = $this->blog->getSameCategoryBlog($category);
		$data['type'] = $category;
		$this->layout->view('page/all-blogs', $data);
	}
	public function search_blog(){
		$this->load->model('blog','',TRUE);
		$allblog= $this->blog->searchBlog($this->input->post('search_tearm'));
		//echo "<pre>";print_r($allblog);exit; 
		$addSearchData = array(
				'searchterm' => $this->input->post('search_tearm'),						
				'ip' => $this->session->userdata('ip_address'),
				'country' => $this->getLocationInfoByIp(),
				'results' => count($allblog)
		);
			
		$this->blog->addblogdata($addSearchData);		
		foreach($allblog as $key =>$value)
		{
      
      if(is_numeric($allblog[$key]['author'])){	  
		  
        $author=$this->user->getUserById($allblog[$key]['author']);
        
        //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
        if($author['displayname']!=""){
          $allblog[$key]['author']=$author['displayname'];
        }else{
        $allblog[$key]['author']="";
        }
      }

	
			$allblog[$key]['tagarray'] = array();
			if($value['tag'] != '')
			{
				$allblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
			}
        $allblog[$key]['blog_image'] = $this->blog->getBlogImageById($allblog[$key]['id']);
        $comment = $this->blog->getAllCommentsByBlog($allblog[$key]['id']);		
        $allblog[$key]['commentcount']  = count($comment);
		}
    $allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		        
        $comment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($comment);
			}
		$data['popular_list'] = $allpopularblog;
		$data['blog'] = $allblog;
		$data['archive_list'] = $this->blog->blogArchive();
		$data['searchterm'] =$this->input->post('search_tearm');
		$data['tag_list'] = $this->blog->getAllBlogTag();
		$data['category_list'] = $this->blog->getAllBlogCategory();
		$this->layout->view('page/search-blogs', $data);
	}

	public function blog_by_cat($slug){
		$slug = urldecode($slug);
		$this->load->model('blog','',TRUE);	
		$allblogs_by_category= $this->blog->BlogsByCategory($slug);

		foreach($allblogs_by_category as $key =>$value)
		{				
      
       if(is_numeric($allblogs_by_category[$key]['author'])){	  
		  
        $author=$this->user->getUserById($allblogs_by_category[$key]['author']);
        
        //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
        if($author['displayname']!=""){
          $allblogs_by_category[$key]['author']=$author['displayname'];
        }else{
        $allblogs_by_category[$key]['author']="";
        }
      }
      
        $allblogs_by_category[$key]['blog_image'] = $this->blog->getBlogImageById($allblogs_by_category[$key]['id']);
        $comment = $this->blog->getAllCommentsByBlog($allblogs_by_category[$key]['id']);		
        $allblogs_by_category[$key]['commentcount']  = count($comment);
		} 
			$allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		        
        $comment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($comment);
			}
		$data['popular_list'] = $allpopularblog;
		$data['archive_list'] = $this->blog->blogArchive();
		$data['searchterm'] =strtoupper($slug);
		$data['blog'] = $allblogs_by_category;
		$data['tag_list'] = $this->blog->getAllBlogTag();
		$data['category_list'] = $this->blog->getAllBlogCategory();
		$this->layout->view('page/search-blogs', $data);
	}
  
  public function blog_by_author($author){    
		$author = urldecode($author);
		$this->load->model('blog','',TRUE);	
    $this->load->model('user','',TRUE);	
		$getUserId= $this->user->getUserIdByDisplayName($author);    
    $allblogs_by_author= $this->blog->BlogsByAuthor($author,$getUserId['id']);
    $serchstring =  urldecode($author);

		foreach($allblogs_by_author as $key =>$value)
		{				
      
         if(is_numeric($allblogs_by_author[$key]['author'])){	  
        
          $author=$this->user->getUserById($allblogs_by_author[$key]['author']);
          
          //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
          if($author['displayname']!=""){
            $allblogs_by_author[$key]['author']=$author['displayname'];
          }else{
          $allblogs_by_author[$key]['author']="";
          }
        }
      
        $allblogs_by_author[$key]['blog_image'] = $this->blog->getBlogImageById($allblogs_by_author[$key]['id']);
        $comment = $this->blog->getAllCommentsByBlog($allblogs_by_author[$key]['id']);		
        $allblogs_by_author[$key]['commentcount']  = count($comment);
		} 
			$allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		        
        $comment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($comment);
			}


		$data['popular_list'] = $allpopularblog;
		$data['archive_list'] = $this->blog->blogArchive();
		$data['searchterm'] =strtoupper($serchstring);
		$data['blog'] = $allblogs_by_author;
		$data['tag_list'] = $this->blog->getAllBlogTag();
		$data['category_list'] = $this->blog->getAllBlogCategory();
		$this->layout->view('page/search-blogs', $data);
	}

	function archive($slug)
	{	

		$this->load->model('blog','',TRUE);	
		$allblogs_archive= $this->blog->BlogByMonth($slug);

		foreach($allblogs_archive as $key =>$value)
		{		
      
        if(is_numeric($allblogs_archive[$key]['author'])){	  
          
            $author=$this->user->getUserById($allblogs_archive[$key]['author']);
            
            //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
            if($author['displayname']!=""){
              $allblogs_archive[$key]['author']=$author['displayname'];
            }else{
            $allblogs_archive[$key]['author']="";
            }
          }
      
      		
        $allblogs_archive[$key]['blog_image'] = $this->blog->getBlogImageById($allblogs_archive[$key]['id']);
        $comment = $this->blog->getAllCommentsByBlog($allblogs_archive[$key]['id']);		
        $allblogs_archive[$key]['commentcount']  = count($comment);
		}

		 $allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{


		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		
        $comment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($comment);
			}
    $data['popular_list'] = $allpopularblog;
		$data['archive_list'] = $this->blog->blogArchive();
		$data['searchterm'] =strtoupper($slug);
		$data['blog'] = $allblogs_archive;
		$data['tag_list'] = $this->blog->getAllBlogTag();
		$data['category_list'] = $this->blog->getAllBlogCategory();
		$this->layout->view('page/search-blogs', $data);


	}

	public function search_by_tag($slug){
		$slug = str_replace("_"," ",$slug);
		$this->load->model('blog','',TRUE);	
		$allblogs_by_tag = $this->blog->BlogsBytag($slug);

		foreach($allblogs_by_tag as $key =>$value)
		{				
          if(is_numeric($allblogs_by_tag[$key]['author'])){	  
          
            $author=$this->user->getUserById($allblogs_by_tag[$key]['author']);
            
            //if $author['displayname'] fetch take it from users['displayname'] table. Otherwise remain same as from blog['author']
            if($author['displayname']!=""){
              $allblogs_by_tag[$key]['author']=$author['displayname'];
            }else{
            $allblogs_by_tag[$key]['author']="";
            }
          }
        
      
	        $allblogs_by_tag[$key]['blog_image'] = $this->blog->getBlogImageById($allblogs_by_tag[$key]['id']);
          $comment = $this->blog->getAllCommentsByBlog($allblogs_by_tag[$key]['id']);		
          $allblogs_by_tag[$key]['commentcount']  = count($comment);
		}
 			$allpopularblog = $this->blog->getAllBlogByPolularity();
			foreach($allpopularblog as $key =>$value)
			{

		
				$allpopularblog[$key]['tagarray'] = array();
				if($value['tag'] != '')
				{
					$allpopularblog[$key]['tagarray'] = $this->blog->getBlogTag($value['tag']);
				}
				$allpopularblog[$key]['blog_image'] = $this->blog->getBlogImageById($allpopularblog[$key]['id']);		
        $comment = $this->blog->getAllCommentsByBlog($allpopularblog[$key]['id']);		
        $allpopularblog[$key]['commentcount']  = count($comment);
			}
			$data['popular_list'] = $allpopularblog;
      $data['archive_list'] = $this->blog->blogArchive();
      $data['searchterm'] =strtoupper($slug);
      $data['blog'] = $allblogs_by_tag;
      $data['tag_list'] = $this->blog->getAllBlogTag();
      $data['category_list'] = $this->blog->getAllBlogCategory();
      $this->layout->view('page/search-blogs', $data);
	}

	public function search_by_tag_prg($slug){
		$slug = urldecode($slug);
		$this->load->model('blog','',TRUE);	
		$alloi_by_tag = $this->oi->oiBytag($slug);

		foreach($alloi_by_tag as $key =>$value)
		{
			$alloi_by_tag[$key]['oistream'] = $this->oi->getHighestRatedAof($alloi_by_tag[$key]['id']);		
			$alloi_by_tag[$key]['school'] = $this->oi->getSchoolData($alloi_by_tag[$key]['school']);
		}
		//exit;
		//echo "<pre>";print_r($searchresult);exit;
		$data['oi']  = $alloi_by_tag;
		$totaloi = $this->oi->getAllOi();
		$data['totaloi'] = count($totaloi);
		$data['streamname'] = ucfirst($slug);
		$this->layout->view('page/other-schools-by-tags', $data);
	}


	public function all_tags($tag)
	{
		$tag = urldecode($tag);
		$this->load->model('blog','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title($tag.' Tag Blog');
		$this->layout->description($tag.' Tag Blog');
		$data = array();
		$data['blog'] = $this->blog->getSameTagBlog($tag);
		$data['type'] = $tag;
		$this->layout->view('page/all-blogs', $data);
	}    
  
  
	public function other_institute_by_areasoffocus($stream)
	{
		$stream = urldecode($stream);
		$this->load->model('oi','',TRUE);
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Browse By '.$stream);
		$this->layout->description('Browse By '.$stream);
		$data = array();
		$searchresult = $this->oi->getOIByStream($stream);
		foreach($searchresult as $key =>$value)
		{
			$searchresult[$key]['oistream'] = $this->oi->getHighestRatedAof($searchresult[$key]['id']);		
			$searchresult[$key]['school'] = $this->oi->getSchoolData($searchresult[$key]['school']);
		}
		//exit;
		//echo "<pre>";print_r($searchresult);exit;
		$data['oi']  = $searchresult;
		$totaloi = $this->oi->getAllOi();
		$data['totaloi'] = count($totaloi);
		$data['streamname'] = $stream;
		$this->layout->view('page/other-schools-by-areasoffocus', $data);
	}
	public function faq()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Frequently Asked Questions');
		$this->layout->description('Frequently Asked Questions');
		$data = array();
		$this->layout->view('page/faq', $data);
	}
	public function terms()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Terms and Conditions');
		$this->layout->description('Terms and Conditions');
		$data = array();
		$this->layout->view('page/terms', $data);
	}
	public function privacy()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
		$this->layout->title('Privacy Statement');
		$this->layout->description('Privacy Statement');
		$data = array();
		$this->layout->view('page/privacy', $data);
	}
	
	public function program_aycg()
	{
		redirect('/learning-retreats/awakening-your-creative-giant', 'refresh');
	}
	public function retreats_aycg()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(1);
		$metades = strip_tags($programData['program_description']);
		$title =$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));

		$this->layout->title('Awakening Your Creative Giant');
		$this->layout->description('Awakening Your Creative Giant');
		$data = array();
		/* variable for blocks start */
		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_awakening();
		/* variable for blocks end */
		$this->layout->view('page/program-aycg', $data);
	}
	public function program_ac()
	{
		redirect('/learning-retreats/amplified-conciousness', 'refresh');
	}
	public function retreats_ac()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
   		$programData = $this->oi->getRpById(2);    
		$metades = strip_tags($programData['program_description']);    
    		$title=$programData['program_title'];		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));

		$this->layout->title('Amplified Conciousness');
		$this->layout->description('Amplified Conciousness');
		$data = array();	
		/* variable for blocks start */
	    	$data['title']=$title;
	    	$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_amplified();
		/* variable for blocks end */
		$this->layout->view('page/program-ac', $data);
	}
	public function program_cv()
	{
		redirect('/learning-retreats/change-ventures', 'refresh');
	}
	
	public function retreats_cv()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(7);
		$metades = strip_tags($programData['program_description']);
		$title=$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));
    
		
		$data = array();
		/* variable for blocks start */
    		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
    
    $StartDateProgramYear = date("Y", strtotime($programData['program_start_date']));
    $EndDateProgramYear = date("Y", strtotime($programData['program_end_date']));
    if($StartDateProgramYear!=$EndDateProgramYear){
        $psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
      $data['Dates'] = $startDate.' - '.$endDate;
    }
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_changeVenture();
		    
		$this->load->helper('directory');    
		$map = directory_map('./images/programs/residency/CV/2013');
		$data['gallery']['2013']=$map;
		$map = directory_map('./images/programs/residency/CV/2014');
		$data['gallery']['2014']=$map;
		$map = directory_map('./images/programs/residency/CV/venue');
		$data['gallery']['venue']=$map;
		
		/* variable for blocks end */
		$this->layout->view('page/program-cv', $data);
	}
	
	
	/*P Created Code start*/
	
	public function how_fearless()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    	$programData = $this->oi->getRpById(7);
		$metades = "How To Be Fearless";
		$title=	"How To Be Fearless";
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));
    
		
		$data = array();
		/* variable for blocks start */
    	$data['title']=$title;
    	$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
    
    $StartDateProgramYear = date("Y", strtotime($programData['program_start_date']));
    $EndDateProgramYear = date("Y", strtotime($programData['program_end_date']));
    if($StartDateProgramYear!=$EndDateProgramYear){
        $psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
      $data['Dates'] = $startDate.' - '.$endDate;
    }
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_changeVenture();
		    
		$this->load->helper('directory');    
		$map = directory_map('./images/programs/residency/CV/2013');
		$data['gallery']['2013']=$map;
		$map = directory_map('./images/programs/residency/CV/2014');
		$data['gallery']['2014']=$map;
		$map = directory_map('./images/programs/residency/CV/venue');
		$data['gallery']['venue']=$map;
		
		/* variable for blocks end */
		$this->layout->view('page/program-hf', $data);
	}
	
	public function ultimate_self_care()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    	$programData = $this->oi->getRpById(7);
		$metades = "Ultimate Self Care";
		$title="Ultimate Self Care";
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));
    
		
		$data = array();
		/* variable for blocks start */
    	$data['title']=$title;
    	$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
    
    $StartDateProgramYear = date("Y", strtotime($programData['program_start_date']));
    $EndDateProgramYear = date("Y", strtotime($programData['program_end_date']));
    if($StartDateProgramYear!=$EndDateProgramYear){
        $psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
      $data['Dates'] = $startDate.' - '.$endDate;
    }
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_changeVenture();
		    
		$this->load->helper('directory');    
		$map = directory_map('./images/programs/residency/CV/2013');
		$data['gallery']['2013']=$map;
		$map = directory_map('./images/programs/residency/CV/2014');
		$data['gallery']['2014']=$map;
		$map = directory_map('./images/programs/residency/CV/venue');
		$data['gallery']['venue']=$map;
		
		/* variable for blocks end */
		$this->layout->view('page/program-ulc', $data);
	}
	
	public function get_unstuck()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    	$programData = $this->oi->getRpById(7);
		$metades = "Get Unstuck";
		$title= "Get Unstuck";
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));
    
		
		$data = array();
		/* variable for blocks start */
    	$data['title']=$title;
    	$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];    
    $StartDateProgramYear = date("Y", strtotime($programData['program_start_date']));
    $EndDateProgramYear = date("Y", strtotime($programData['program_end_date']));
    if($StartDateProgramYear!=$EndDateProgramYear){
        $psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
      $data['Dates'] = $startDate.' - '.$endDate;
    }
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_changeVenture();
		    
		$this->load->helper('directory');    
		$map = directory_map('./images/programs/residency/CV/2013');
		$data['gallery']['2013']=$map;
		$map = directory_map('./images/programs/residency/CV/2014');
		$data['gallery']['2014']=$map;
		$map = directory_map('./images/programs/residency/CV/venue');
		$data['gallery']['venue']=$map;
		
		/* variable for blocks end */
		$this->layout->view('page/program-gu.php', $data);
	}
	
	/*P create code end*/
  
  
	public function program_ch()
	{
		redirect('/learning-retreats/culture-hack', 'refresh');
	}
	public function retreats_ch()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(3);
		$metades = strip_tags($programData['program_description']);
		$title=$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));

		$this->layout->title('Culture Hack');
		$this->layout->description('Culture Hack');
		$data = array();
		/* variable for blocks start */
    		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_cultureHack();
		/* variable for blocks end */
		$this->layout->view('page/program-ch', $data);
	}
	
	public function program_gooyh()
	{
		redirect('/learning-retreats/get-out-of-your-head', 'refresh');
	}
	public function retreats_gooyh()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(4);
		$metades = strip_tags($programData['program_description']);
		$title=$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));

		$this->layout->title('Get out of your Head');
		$this->layout->description('Get out of your Head');
		$data = array();
		/* variable for blocks start */
    		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_getoutHead();
		/* variable for blocks end */
		$this->layout->view('page/program-gooyh', $data);
	}
	public function program_lbn()
	{
		redirect('/learning-retreats/life-by-numbers', 'refresh');
	}
	public function retreats_lbn()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(5);
		$metades = strip_tags($programData['program_description']);		
    		$title=$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));

		$this->layout->title('Life by Numbers');
		$this->layout->description('Life by Numbers');
		$data = array();
		/* variable for blocks start */
    		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['programtext'] = $programData['program_text'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_lifebynumbers();
		/* variable for blocks end */
		$this->layout->view('page/program-lbn', $data);
	}
	public function program_pitaom()
	{
		redirect('/learning-retreats/productivity-in-the-age-of-mindfulness', 'refresh');
	}
	
	public function retreats_pitaom()
	{
		$this->load->library('layout');          // Load layout library
		$this->layout->layout_view = 'layout/default.php';
    		$programData = $this->oi->getRpById(6);
		$metades = strip_tags($programData['program_description']);
		$title=$programData['program_title'];
		
		$psdates = date_parse($programData['program_start_date']);
		$pedates = date_parse($programData['program_end_date']);
		$psyear = $psdates['year'];
		$peyear = $pedates['year'];
		if($peyear != $psyear)
		{
			$startDate = date("d F Y", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		else
		{
			$startDate = date("d F", strtotime($programData['program_start_date']));
			$endDate = date("d F Y", strtotime($programData['program_end_date']));
		}
		
		$earlybdeadline = date("Y/m/d", strtotime($programData['early_bird_deadline']));
		$applicationdeadline = date("Y/m/d", strtotime($programData['application_deadline']));		
		$data = array();
		/* variable for blocks start */
    		$data['title']=$title;
    		$data['metades']=$metades;
		$data['FormFieldProgram'] = $programData['program_abbreviation'];
		$data['Location'] = $programData['program_location'];
		$data['Dates'] = $startDate.' - '.$endDate;
		$data['Duration'] = $programData['program_duration'];
		$data['PriceTwin'] = $programData['price_twin'];
		$data['PriceTwinEarly'] = $programData['price_twin_earlybird'];
		$data['PriceQueen'] = $programData['price_queen'];
		$data['PriceQueenEarly'] = $programData['price_queen_earlybird'];
		$data['PricePremium'] = $programData['price_premium'];
		$data['DateEarly'] = $earlybdeadline;

		$data['DateApplication'] = $applicationdeadline;
		$data['contents']=$this->oi->getResidency_productivity();
		/* variable for blocks end */
		$this->layout->view('page/program-pitaom', $data);
	}
	function addFrontUser(){
		$this->load->model('user','',TRUE);
		 if($this->input->post()){	
			 
			if($this->user->checkUniqueEmail($this->input->post('r_email'))){ 
				 
				$data = array(
				'username' => $this->input->post('r_username'),
				'displayname' => $this->input->post('r_displayname'),
				'password' => md5($this->input->post('r_password')),
				'role' => 3,
				'email' => $this->input->post('r_email')
				);
				$last_user_id = $this->user->addUser($data);
				echo "useradded";
				exit;
			}else{
				echo "duplicateemail";
				exit;
			}
		}
		echo "notadded";
		exit;
	}
	public function frontlogin()
	{
		
		$this->load->model('user','',TRUE);
		if($this->input->post()){ 
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = $this->user->frontuserlogin($username, $password);			
			
			if($result)
			{
				$sess_array = array();
				foreach($result as $row)
				{
					$sess_array = array(
					'id' => $row->id,
					'username' => $row->username,
					'role' => $row->role
					);
					$this->session->set_userdata('logged_in', $sess_array);
				}
				
				
				$log['username'] = $this->user->getUser($username);
				$log['ipaddress'] = $this->session->userdata('ip_address');
				$log['action'] = "Successful Login";
				$log['datetime'] = date('Y-m-d h:i:s');
				$log['status'] = 1;

				if($log['username'] !="" )
				{
					 $logdata = $this->user->addLog($log);
				}
				if(isset($_SERVER['HTTP_REFERER']))
                {
                    $redirect_to = $_SERVER['HTTP_REFERER'];
                    $page_is_blog_or_not_array = explode("/",$redirect_to);
                    $page_is_blog_or_not = $page_is_blog_or_not_array[count($page_is_blog_or_not_array)-2];
                    $result_login['referer_url'] = $redirect_to;
                    $result_login['page'] = $page_is_blog_or_not;
                }
				$result_login['status_login'] = "loginsuccess";
				$result_login['role'] = $sess_array['role'];
				echo json_encode($result_login);exit;
			}
			else
			{
				
				$log['username'] = $this->user->getUser($username);
				$log['ipaddress'] = $this->session->userdata('ip_address');
				$log['action'] = "Login Fail";
				$log['datetime'] = date('Y-m-d h:i:s');
				$log['status'] = 0;

				if($log['username'] !="" ){

				$logdata = $this->user->addLog($log);
				}
				$result_login['status_login'] = "loginfail";
				echo json_encode($result_login);exit;
				
			}
		}
	}
	function logout()
	{
		$session_data = $this->session->userdata('logged_in');
		$log['username'] = $session_data['username'];
		$log['ipaddress'] = $this->session->userdata('ip_address');
		$log['action'] = "User Logout";
		$log['datetime'] = date('Y-m-d h:i:s');
		$log['status'] = 1;
		$logdata = $this->user->addLog($log);
		$this->session->unset_userdata('logged_in');
		redirect('home', 'refresh');
	}
	function locationofuser($user_ip_address){

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$user_ip_address));
		if($ip_data && $ip_data->geoplugin_countryName != null){
		$user_country_code = $ip_data->geoplugin_countryCode;
		}
		else{
		$user_country_code="N/A";
		}
		return $user_country_code;
	}  
	
}
