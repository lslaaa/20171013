<?php

/* * *********
  jack by code 2009-12-31
  读数据库，不允许写操作
 * ********* */

class mysqlDBA {

	var $db = null;
	var $dbw = null;

	//析构函数
	public function __construct() {
		global $_SGLOBAL;
		$this->db = $_SGLOBAL['db'];
		$this->dbw = $_SGLOBAL['dbw'];
		$this->trans_db = $_SGLOBAL['trans_db'];
	}

	//获取总记录数
	function getTotalSize() {
		$sSql = "SELECT FOUND_ROWS() total;";
		$sValue = $this->db->get_Value($sSql);

		return $sValue;
	}
	
	function admin($sTable) {
	        return ' `admin_' . $sTable.'`';
	    }
	
	function index($sTable) {
	        return ' `index_' . $sTable.'`';
	    }

	/*	 * ***************************
	 * Function Name:  queryPage
	 * function remark: Statistical query record
	 * create by jack
	 * modify date: 2010-12-21
	 * ************************** */

	function page_start($iPage, $iPagesize) {
		if ($iPage > 0) {
			$iStart = ($iPage - 1) * $iPagesize;
		} else {
			$iStart = 0;
		}
		return " limit $iStart,$iPagesize";
	}

}

?>