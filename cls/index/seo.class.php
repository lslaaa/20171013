<?php

!defined('LEM') && exit('Forbidden');

class LEM_seo extends mysqlDBA {
   

    function get() {
        $str_sql = "SELECT * FROM " . $this->index('seo');
        return $this->db->get_one($str_sql);
    }
	
	function update($arr_data){
		$arr_seo = $this->get();
		if(empty($arr_seo)){
			$str_sql = "INSERT INTO " . $this->index('seo').make_sql($arr_data,'insert');
		}
		else{
			$str_sql = "UPDATE " . $this->index('seo').' SET '.make_sql($arr_data,'update');
		}
		return $this->db->query($str_sql);
	}
}

?>