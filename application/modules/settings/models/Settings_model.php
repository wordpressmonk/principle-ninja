<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all settings values from the DB
     *
     * @return  object   $q->result
     */
    public function get_all()
    {
	    
    }

    /**
     * Get value by name
     *
     * @param   string   $name
     * @return  array    $data
     */
    public function get_by_name($name)
    {
        $data = array();
        $this->db->where('name', $name);
        $this->db->limit(1);
        $q = $this->db->get('settings');

        if ( $q->num_rows() > 0 ) 
        {
            if ($q->num_rows() > 0)
            {
                $data = $q->row_array();
            }

            $q->free_result();
            return $data;
        }
        else
        {
            return false;
        }
    }


    /**
     * updates the settings
     *
     * @param   array    $value
     * @return  void
     */
    public function update($settings)
    {
	    
        foreach($settings as $name => $value)
        {

            $this->db->where('name', $name);
            $this->db->delete('settings');

            $data = array(
                'name' => $name,
                'value' => $value
            );

            $this->db->insert('settings', $data);

        }

    }

}