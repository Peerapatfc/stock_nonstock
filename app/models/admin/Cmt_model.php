<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Cmt_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function getAllComments()
    {

        $q = $this->db->get("notifications");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getNotifications()
    {
        $date = date('Y-m-d H:i:s', time());
        $this->db->where("from_date <=", $date);
        $this->db->where("till_date >=", $date);
        if (!$this->Owner) {
            if ($this->Supplier) {
                $this->db->where('scope', 4);
            } elseif ($this->Customer) {
                $this->db->where('scope', 1)->or_where('scope', 3);
            } elseif (!$this->Customer && !$this->Supplier) {
                $this->db->where('scope', 2)->or_where('scope', 3);
            }
        }
        $q = $this->db->get("notifications");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCommentByID($id)
    {

        $q = $this->db->get_where("notifications", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;

    }


    public function addNotification($data)
    {

        if ($this->db->insert("notifications", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateNotification($id, $data)
    {
        $this->db->where('id', $id);
        if ($this->db->update("notifications", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteComment($id)
    {
        if ($this->db->delete("notifications", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }


	
	#start Bank_tranfer woo
	public function updateBanktranfer($id, $data)
    {
        $this->db->where('id', $id);
        if ($this->db->update("banktransfer", $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function getBanktranferByID($id)
    {
        $q = $this->db->get_where("banktransfer", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	
    public function addBanktransfer($data)
    {
        if ($this->db->insert("banktransfer", $data)) {
            return true;
        } else {
            return false;
        }
    }
	
    public function deleteBank($id)
    {
        if ($this->db->delete("banktransfer", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    public function bankthailand()
	{
        $sst = array(
			'1' => lang('Bangkok Bank'),
			'2' => lang('Krungthai Bank'),
			'3' => lang('Siam Commercial Bank'),
			'4' => lang('Kasikorn Bank'),
			'5' => lang('Bank of Ayudhya'),
			'6' => lang('Thanachart Bank'),
			'7' => lang('TMB Bank'),
			'8' => lang('Kiatnakin Bank')
		);
		return $sst;
	}
	
	#End Bank_tranfer Wow
}

/* End of file pts_model.php */
/* Location: ./application/models/pts_types_model.php */
