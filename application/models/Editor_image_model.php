<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Editor image model class
 */

class Editor_image_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'editor_image';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'eim_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'editor_image.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'editor_image.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_editor_file_by_date($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(eim_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(eim_datetime, 10) >=', $start_date);
        $this->db->where('left(eim_datetime, 10) <=', $end_date);
        $this->db->group_by('day');
        $this->db->order_by('eim_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }
}
