<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getUserByCompany($phone = null)
    {
        $this->db->select('users.email')
            ->join('companies', 'companies.company = users.company', 'left');

		$this->db->where("users.email = companies.email");
		$this->db->where("companies.group_name = 'user'");
		$this->db->where("users.phone = '$phone'");
		
		$q = $this->db->get('users');
	    if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			return $data;
        }
        return FALSE;
    }
}
