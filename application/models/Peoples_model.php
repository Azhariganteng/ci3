<?php

class Peoples_model extends CI_Model
{
    public function getAllPeoples()
    {
        //ambil k db                       hslny=array
        return $this->db->get('peoples')->result_array();
    }

    public function getPeoples($limit, $start, $keyword = null)
    {
        if ($keyword) {
            //s*f whre like
            $this->db->like('name', $keyword);
            // $this->db->or_like('email', $keyword);
        }
        return $this->db->get('peoples', $limit, $start)->result_array();
    }

    public function countAllPeoples()
    {
        //ngitung        SELECT*FROM peopel
        return $this->db->get('peoples')->num_rows();
    }
}
