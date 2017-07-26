<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class user_model extends Default_model
{
    function __construct()
    {
        parent::__construct();
        $this->table = 'users';
        $this->users_hash_table = 'users_hash';
        //$this->user_transactions_table = $this->config->item('user_transactions','tables');
//    users_table
        /*
        $this->pex_table = $this->config->item('dle_pex','tables');
        $this->pex_ent_table = $this->config->item('dle_pex_entity','tables');
        $this->pex_inh_table = $this->config->item('dle_pex_inher','tables');
        $this->banlist_table = $this->config->item('dle_banlist','tables');
        $this->unbans_table = $this->config->item('dle_unbans','tables');
        $this->db_dle = $this->load->database($this->config->item('db_group_dle'), TRUE, TRUE);
        */
    }


    function is_duplicate_user($user_mail, $user_name)
    {
        /*
            $this->db_dle->select('name')->from($this->users_table);
            $this->db_dle->where('LOWER(name)', strtolower($user_name));
            $this->db_dle->where('LOWER(email)', strtolower($user_mail));
            $query = $this->db_dle->get();
            $result = $query->row();
            if (!empty($result)){
                return true;
            } else {
                return false;
            }
        */
    }


    function login($email_nick, $pass)
    {
        $this->db->select('*')->from($this->table);
        $where = 'password = "' . $pass . '" AND (email = "' . $email_nick . '" OR login = "' . $email_nick . '")';
        $this->db->where($where);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function getUserById($user_id)
    {
        $this->db->select('*');
        $query = $this->db->get_where($this->table, array('id' => $user_id));
        return $query->row();
    }

    function getUserByEmail($email)
    {
        $this->db->select('*');
        $query = $this->db->get_where($this->table, array('email' => $email));
        return $query->row();
    }

    function get_user_by_login($user_login)
    {
        $this->db->select('*');
        $query = $this->db->get_where($this->table, array('login' => $user_login));
        return $query->row();
    }


    function save_hash($id, $hash, $key)
    {
        $query = $this->db->get_where($this->users_hash_table, array('user_id' => $id, 'key' => $key));
        $row = $query->row();
        if (empty($row)) {
            $this->db->insert($this->users_hash_table, array(
                'user_id' => $id,
                'hash' => $hash,
                'key' => $key,
            ));
        } else {
            $this->db->where('user_id', $id);
            $res = $this->db->update($this->users_hash_table, array(
                'hash' => $hash,
                'key' => $key,
            ));
        }
    }

    function get_users_count()
    {
        $sql = "SELECT
             count(*) as count
        FROM " . $this->users_table;
        $query = $this->db_dle->query($sql);
        $result = $query->row();
        return $result->count;
    }

    function login_user($user_name, $user_pass)
    {
        $this->db->where('name', $user_name);
        $this->db->where('password', $user_pass);
        $query = $this->db->get($this->users_table);
        return $query->row();
    }

    function save_user($aData, $action = 'add', $user_id = '')
    {
        if ($action == 'add') {
            $this->db->insert($this->table, $aData);
            $res = $this->db->insert_id();
        } else {
            if (!$user_id) return false;
            $this->db->where('id', $user_id);
            $res = $this->db->update($this->table, $aData);
        }
        if (!$res) {
            return false;
        } else {
            return $res;
        }
    }

    function update_user($user_id, $aData)
    {
        $this->db->where('id', $user_id);
        return $this->db->update($this->table, $aData);
    }

    public function getUserByHash($hash, $key, $life_time)
    {
        $this->db->select('*');
        $this->db->from($this->users_hash_table);
        $this->db->where('hash', $hash);
        $this->db->where('key', $key);
        $this->db->where('time >', time() - $life_time);
        $query = $this->db->get();
        //dump($this->db->last_query(),0);
        return $query->row();

    }

    function deleteHashByKey($user_id, $key)
    {
        $this->db->where('key', $key);
        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->users_hash_table);
    }

    function deleteHashOutdated($key, $life_time)
    {
        $this->db->where('key', $key);
        $this->db->where('time <', date('YYYY-MM-DD HH:MM:SS', time() - $life_time));
        return $this->db->delete($this->users_hash_table);
    }

    function getUserbyFilter($fp_aFilter, $fp_sidx, $fp_sord)
    {
        $this->db_dle->select('*');
        $this->db_dle->order_by($fp_sidx, $fp_sord);
        if (!isset($fp_aFilter['limit'])) {
            $query = $this->db_dle->get_where($this->users_table, $fp_aFilter['fields']);
        } else {
            $query = $this->db_dle->get_where($this->users_table, $fp_aFilter['fields'], $fp_aFilter['limit'], $fp_aFilter['offset']);
        }
        //dump($this->db_dle->last_query());
        return $query->result();
    }

    function del_user($fp_rowId)
    {
        foreach ((array)$fp_rowId as $k => $id) {
            $this->db->or_where('id', $id);
        }
        $this->db->delete($this->table);
        return true;
    }

    function save_user_transaction($user_id, $cost, $reason, $type = 'pay')
    {
        $aData = array(
            'user_id' => $user_id,
            'cost' => $cost,
            'reason' => $reason,
            'type' => $type
        );
        return $this->db->insert($this->user_transactions_table, $aData);
    }

    function get_all_users()
    {
        $this->db->select('*')->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

    function get_user_by_id_nick($user_inf)
    {
        $this->db->select('*')->from($this->table);
        $this->db->where("id = '".$user_inf."' OR login = '".$user_inf."'");
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function setLastAction($id){
        return $this->update(array('last_action' => time()), array('id' => $id));
    }
    function getUsers($id, $search = false){
        $where = '';
        if($id)
            $where .= "id != ".$id.' ';
        if($search)
            $where .= 'AND (fname like("%'.$search.'%") 
                     OR lname like("%'.$search.'%") 
                     OR CONCAT(fname, lname) like("%'.$search.'%")
                     OR CONCAT(fname," ",lname) like("%'.$search.'%")
                     OR CONCAT(lname, fname) like("%'.$search.'%")
                     OR CONCAT(lname," ",fname) like("%'.$search.'%"))';

        return $this->getDataByWhere($where);
    }

    function getInfo($id){
        $this->db->select("u.*, g.name AS group_name, i.album as avatar_album, i.src AS avatar_file")
            ->from("users u")
            ->join('groups g', 'g.id = u.group', 'left')
            ->join('images i', 'i.id = u.avatar', 'left')
            ->where("u.id='".$id."'");

        $query = $this->db->get();
        return $query->result();
    }

    function getSettings($id){
        $this->setTable('users_settings');
        $result = $this->getDataByWhere(array('user_id' => $id), '*');
        
        if($result){
            $this->setTable('users');
            return $result;
        }else{
            if($this->db->save(array('user_id' => $id), 'add')){
                $this->setTable('users');
                return $this->getSettings($id);
            }
        }
        $this->setTable('users');
    }

    function getPerms($id){
        $this->setTable('users_perms');
        $result = $this->getDataByWhere(array('user_id' => $id), '*');
        
        if($result){
            $this->setTable('users');
            return $result;
        }else{
            if($this->db->save(array('user_id' => $id), 'add')){
                $this->setTable('users');
                return $this->getPerms($id);
            }
        }
    }

    function savePerms($id, $data){
        $this->setTable('users_perms');
        if($this->db->update(array('user_id' => $id), $data)){
            $this->setTable('users');
            return true;
        }
        $this->setTable('users');
        return false;
    }

    public $without = array();

    function getFriends($id,$count = 99,$page = 1){
        $without = array();$s = '';

        $where = "(f.user1 = $id OR f.user2 = $id)";
        if(isset($without[0]))
            $where .= " AND u.id NOT IN ($without)";

        $this->db->select('*')
            ->from('friends f')
            ->join('users u', '(f.user1=u.id OR f.user2 = u.id) AND u.id != '. $id, 'left')
            ->where($where);

        $query = $this->db->get();
        return $query->result();
    }

    function getFriendsOnline($id,$count = 99,$page = 1){
        /*$sql = "SELECT * 
                FROM ".$this->config['dbprefix']."friends f 
                LEFT JOIN ".$this->config['dbprefix']."users u ON((f.user1=u.id OR f.user2 = u.id) AND u.id != $id) 
                WHERE (f.user1 = $id OR f.user2 = $id) AND u.last_action >= ".(time() - 900);*/

        $without = array();$s = '';

        $where = "(f.user1 = ".$id." OR f.user2 = ".$id.") AND u.last_action >= ".(time() - 900);

        $this->db->select('*')
            ->from('friends f')
            ->join('users u', '(f.user1=u.id OR f.user2 = u.id) AND u.id != '. $id, 'left')
            ->where($where);

        $query = $this->db->get();
        return $query->result();
    }

    function getFriendsReq($id,$count = 15,$page = 1){
        $this->db->select('* ,u.id AS user_from_id')
            ->from('friends_req r')
            ->join('users u', 'u.id = r.from_user', 'left')
            ->where('r.to_user', $id);

        $query = $this->db->get();
        return $query->result();
    }

    function delFriend($u1, $u2){
        if($u1 == $u2) return false;

        if($this->db->delete("friends", '(user1 = '.$u1.' AND user2 = '.$u2.') OR (user1 = '.$u2.' AND user2 = '.$u1.')'))
            return true;
        return false;
    }

    function sendReq($from_user,$to_user){
        $data = array('from_user' => $from_user, 'to_user' => $to_user);
        if($this->db->insert("friends_req",$data))
            return true;
        return false;
    }

    function subscribe($from_user,$to_user){
        $data = array('user_from_id' => $from_user, 'user_to_id' => $to_user);
        if($this->db->insert("subscribes", $data))
            return true;
        return false;
    }
    function unsubscribe($from_user,$to_user){
        $data = array('user_from_id' => $from_user, 'user_to_id' => $to_user);
        if($this->db->delete("subscribes", $data))
            return true;
        return false;
    }

    function reqCancel($from_user,$to_user){
        if($from_user == $to_user) return false;

        $data = array('from_user' => $from_user, 'to_user' => $to_user);
        if($this->db->delete("friends_req",$data))
            return true;
        return false;
    }

    function reqOk($from_id, $to_id){
        if($this->db->delete("friends_req", array('to_user' => $to_id, 'from_user' => $from_id))){
            $data = array('user1' => $from_id ,'user2' => $to_id);
            if($this->db->insert("friends", $data))
                return true;
        }
        return false;
    }

    function reqNo($from_id, $to_id){
        if($this->db->delete("friends_req", array('to_user' => $to_id, 'from_user' => $from_id))){
            return true;
        }
        return false;
    }

    function checkReq($from_user, $to_user){
        $this->db->select('count(*) as count')
            ->from('friends_req')
            ->where(array('from_user' => $from_user, 'to_user' => $to_user));

        $query = $this->db->get();
        $result = $query->row();
        if($result->count > 0)
            return true;
        return false;
    }

    function checkSubscribe($from_user, $to_user){
        $this->db->select('count(*) as count')
            ->from('subscribes')
            ->where(array('user_from_id' => $from_user, 'user_to_id' => $to_user));

        $query = $this->db->get();
        $result = $query->row();
        if($result->count > 0)
            return true;
        return false;
    }

    function checkFriends($u1,$u2){
        if($u1 == $u2) return false;

        $this->db->select('count(*) as count')
            ->from('friends')
            ->where('(user1 = '.$u1.' AND user2 = '.$u2.') OR (user1 = '.$u2.' AND user2 = '.$u1.')');

        $query = $this->db->get();
        $result = $query->row();
        if($result->count > 0)
            return true;
        return false;
    }

    function checkFriendsReq($from_user,$to_user){
        if($from_user == $to_user) return false;

        $this->db->select('count(*) as count')
            ->from('friends_req')
            ->where('from_user = '.$from_user.' AND to_user = '.$to_user);

        $query = $this->db->get();
        $result = $query->row();
        if($result->count > 0)
            return true;
        return false;
    }

    function countFriendsReq($uid = false){
        if(!$uid) return false;

        $this->db->select('count(id) as count')
            ->from('friends_req')
            ->where('to_user = '.$uid);

        $query = $this->db->get();
        $result = $query->row();
        if($result->count > 0)
            return true;
        return false;
    }

}