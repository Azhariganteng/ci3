<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        //join
        $query = "SELECT usm.*, um.menu
FROM user_sub_menu as usm JOIN user_menu as um
ON usm.menu_id = um.id
        ";
        return $this->db->query($query)->result_array();
    }
}
