<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sequence_model extends CI_Model
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    
    public function getSequence()
    {
        $q = $this->db->get_where('sequence' ,array('1' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    
    public function updateSequence( $data = array())
    {
        
        if ($this->db->update('sequence', $data)) {
            return true;
        }
        return false;
    }

    
    
    
}