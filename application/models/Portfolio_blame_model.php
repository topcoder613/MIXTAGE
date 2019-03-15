<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_blame model class
 */

class Portfolio_blame_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_blame';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pbl_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'portfolio_blame.*, portfolio.por_content, portfolio.por_category, portfolio.por_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'portfolio_blame.user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'portfolio', 'on' => 'portfolio_blame.por_id = portfolio.por_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_blame_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(pbl_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(pbl_datetime, 10) >=', $start_date);
        $this->db->where('left(pbl_datetime, 10) <=', $end_date);

        $this->db->group_by('day');
        $this->db->order_by('pbl_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;

    }
}
