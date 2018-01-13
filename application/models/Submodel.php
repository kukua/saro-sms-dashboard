<?php
error_reporting(0);
class Submodel extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}
	
	function results($table,$attribute,$where,$groupby = null,$limit = null, $orderby = null)
	{
		$this->db->select($attribute);
		
		$this->db->from($table);
		
		sizeof($where)==0 ? : $this->db->where($where);
		
		sizeof($groupby)==0 ? : $this->db->group_by($groupby);
		
		sizeof($orderby)==0 ? : $this->db->order_by($orderby);
		
		if(sizeof($limit)==0 )
		{
			
		}else{
		
			$this->db->limit($limit);
		}
		
		return $this->db->get();
		
		
	}
	
	function insert($table,$array = array())
	{
		$this->db->insert($table,$array);
	}
	
	function update($table = null,$values = null,$where = null)
	{
		sizeof($where)==0 ? $this->db->update($table,$values) : $this->db->update($table,$values,$where);
		
	}
	
	public function get_community_maximum_length()
	{
		$result =  $this->submodel->results('settings_community_length','community_length',null);
		
		$length = '';
		
		foreach( $result->result() as $rows)
		{
			$length = $rows->community_length;
		}
		
		return $length;
		
	}
	
	public function get_tier_length()
	{
		$result =  $this->submodel->results('settings_maximum_level','maximum_level ',null);
		
		$length = '';
		
		foreach( $result->result() as $rows)
		{
			$length = $rows->maximum_level;
		}
		
		return $length;
		
	}
	
	public function get_num_unread_notification($mobile)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('notifications','id',array('receiver'=>$mobile,'status'=>1,'delete_status'=>0));
		
		return $result->num_rows();
	}
	
	
	public function get_num_children($mobile)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','count(*) as counts',array('parent_'=>$mobile, 'paired'=>1));
		
		$counts = 0;
		
		foreach($result->result() as $rows)
		{
			$counts = $rows->counts;
		}
		
		return $counts;
	}
	
	public function getUsers($mobile = null)
	{
		//$this->load->model('submodel');
		$result = '';
		if($mobile == '')
		{
		$result =  $this->submodel->results('subscribers','count(*) as counts',array( 'paired'=>1));
		}else{
		$result =  $this->submodel->results('subscribers','count(*) as counts',array( 'parent_'=>$mobile, 'paired'=>1));	
		}
		
		$counts = 0;
		
		foreach($result->result() as $rows)
		{
			$counts = $rows->counts;
		}
		
		return $counts;
	}
	
	public function getTotalUsers()
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','count(*) as counts',null);
		
		$counts = 0;
		
		foreach($result->result() as $rows)
		{
			$counts = $rows->counts;
		}
		
		return $counts;
	}
	
	
	public function get_unread_notification($mobile)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('notifications','*',array('receiver'=>$mobile,'status'=>1,'delete_status'=>0),null,'5','id DESC');
		
		return $result;
	}
	
	public function get_num_sent_messages($mobile, $status)
	{
		$result =  $this->submodel->results('senditems','id',array('sender'=>$mobile,'status'=>$status));
		return $result->num_rows();
	}
	
	
	public function get_inbox($mobile, $limit)
	{
	
		$result =  $this->submodel->results('notifications','*',array('receiver'=>$mobile, 'delete_status'=>0),null,$limit,'id DESC');
		
		return $result;
	}
	

	
	public function message_details($id)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('notifications','*',array('id'=>$id,'delete_status'=>0));
		
		return $result;
	}
	
	public function get_notice()
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('notice','*',array('status'=>1));
		
		return $result;
	}
	
	public function get_communities($mobile, $limit = null)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','*',array('parent_'=>$mobile, 'paired'=>1),null,$limit);
		
		return $result->num_rows();
	}
	
	public function get_communities_admin($mobile, $limit = null)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','*',null,null,$limit);
		
		return $result->num_rows();
	}
	
	public function get_followers($mobile,$limit = null)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','*',array('parent_'=>$mobile, 'paired'=>1),null,$limit,'id DESC');
		
		return $result;
	}
	
	public function get_followers_admin($mobile,$limit = null)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','*',null,null,$limit,'id DESC');
		
		return $result;
	}
	
	public function online_visits()
	{
			$result =  $this->submodel->results('login_details','count(*) as number',array('parent_'=>$this->session->userdata('parent_')));
		
		$number = 0;
		
		foreach($result->result() as $rows)
		{
			$number = $rows->number;
		}
		return $number;
	}
	public function login_logs($limit = null)
	{
		
		$result =  $this->submodel->results('login_details','*',array('parent_'=>$this->session->userdata('parent_')),null,$limit,'id DESC');
		
		return $result;
	}
	
	public function login_logs_admin($limit = null)
	{
		
		$result =  $this->submodel->results('login_details','*',null,null,$limit,'id DESC');
		
		return $result;
	}
	
	public function get_visits($mobile)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('login_details','*',array('parent_'=>$mobile));
		
		return $result->num_rows();
	}
	
	public function get_visits_admin($mobile = null)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('login_details','*',null,'parent_');
		
		return $result->num_rows();
	}
	
	public function get_account_balance($mobile)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('remunerations','sum(amount) as amount',array('status'=>'PENDING','receiver'=>$mobile));
		
		$amount = 0;
		
		foreach($result->result() as $rows)
		{
			$amount = $rows->amount;
		}
		
		return $amount;
	}
	
	public function getparentById($id)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','child',array('id'=>$id));
		
		$child = 0;
		
		foreach($result->result() as $rows)
		{
			$child = $rows->child;
		}
		
		return $child;
	}
	
	
	public function getparentBychild($id)
	{
		//$this->load->model('submodel');
		$result =  $this->submodel->results('subscribers','parent_',array('child'=>$id));
		
		$child = 0;
		
		foreach($result->result() as $rows)
		{
			$child = $rows->parent_;
		}
		
		return $child;
	}
	
	
		public function getnameBychild($id)
		{
		
			$result =  $this->submodel->results('subscribers','f_name,l_name',array('child'=>$id));
			
			$fullname = 0;
			
			foreach($result->result() as $rows)
			{
				$fullname = $rows->f_name.' '.$rows->l_name;
			}
			
			return $fullname;
		}
		
		public function getnameBywhere($where)
		{
		
			$result =  $this->submodel->results('subscribers','f_name,l_name',$where);
			
			$fullname = 0;
			
		
			
			foreach($result->result() as $rows)
			{
				
				$fullname = $rows->f_name.' '.$rows->l_name;
			}
			
			return $fullname;
		}
	
		
		public $network = array();
	
		public function parseNetwork($subscriber){
		
			$result = $this->submodel->results('subscribers','child,parent_',array('parent_'=>$subscriber,'paired'=>1));
			
			$index = 0;
			
			foreach( $result->result() as $row )
			{
				
				//$this->network["'".$row->parent_."'"][$index] = $row->child;
				$this->network["'".$row->parent_."'"][$index] = $row->child;
				$this->parseNetwork($row->child); //Parse again
				
				$index++;
				
			}
			
			$this->session->set_userdata('network',$this->network);			//print_r($this->network);
			
		}
		
		public function remuneration_history($limit = null)
		{
			
				$this->db->select('*');
		
				$this->db->from('remunerations');
		
				$this->db->where(array('receiver'=>$this->session->userdata('parent_')));
		
				$this->db->order_by('id DESC');
		
			
				
				if(strlen($limit) > 0 and $limit > 1)
				{
					
					
					
					//$start = ($limit-1)*5+1<0?($limit)*5+1:($limit-1)*5+1;
					
					$start = $limit * 15 - 15;
					
				//	echo $start;
					
				$limit = array(15,$start);
				
				}else{
						$limit = array(15,0); //Number of rows , start from
					
				}
				
				
				
				$this->db->limit($limit[0],$limit[1]);
	
		
				return $this->db->get();
		
			
		}
		
		
		public function community_growth($parent = null )
		{
			
				$this->db->select('*');
		
				$this->db->from('subscribers');
		
				$this->db->where(array('parent_'=>$parent, 'paired'=>1));
		
				$this->db->order_by('id DESC');
		
			
				
			
	
		
				return $this->db->get();
		
			
		}
		
		public function community_growth_admin($parent = null, $limit = null )
		{
			
				$this->db->select('*');
		
				$this->db->from('subscribers');
		
				
		
				$this->db->order_by('id DESC');
		
			
				if(strlen($limit) > 0 and $limit > 1)
				{
					
					
					
					//$start = ($limit-1)*5+1<0?($limit)*5+1:($limit-1)*5+1;
					
					$start = $limit * 15 - 15;
					
				//	echo $start;
					
				$limit = array(15,$start);
				
				}else{
						$limit = array(15,0); //Number of rows , start from
					
				}
				
				
				
				$this->db->limit($limit[0],$limit[1]);
	
				
			
	
		
				return $this->db->get();
		
			
		}
		
		
		public function remuneration_num()
		{
			$result =  $this->submodel->results('remunerations','count(*) as num',array('receiver'=>$this->session->userdata('parent_')));
			$num  = 0;
			foreach($result->result() as $rows)
			{
				$num = $rows->num;
				
			}
			
			return $num;
		}
		
		public function community_num()
		{
			$result =  $this->submodel->results('subscribers','count(*) as num');
			$num  = 0;
			foreach($result->result() as $rows)
			{
				$num = $rows->num;
				
			}
			
			return $num;
		}
		
		
		
		public function sendOrangeMoney($mobile,$amount)
		{
			
			
		}
		
		public function SendSMS($mobile,$sender,$message)
		{
			
			
			
			$url = 'http://121.241.242.114:80/sendsms?username=gpm-system&password=gpsms&type=0&dlr=1&destination='.urlencode($mobile).'&source='.$sender.'&message='.urlencode($message).'';
			
			
			file($url);
			
			
		}
		
		
		public function instant_remuneration_state($mobile)
		{
			$result =  $this->submodel->results('subscribers','instant_remuneration',array('parent_'=>$mobile));
			$num  = 0;
			foreach($result->result() as $rows)
			{
				$num = $rows->instant_remuneration;
				
			}
			
			return $num;
		}
		
		//public function 
	
	
}

?>