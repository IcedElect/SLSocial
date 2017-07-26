<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Wall_model extends Default_model
{
	public $user_id = 0;
	function __construct()
    {
        parent::__construct();
        $this->table = 'wall';
    }

    function getPostsByWall($id,$limit = 10,$page = 0){
        $offset = ($page) * $limit;
        
        /*$sql = "SELECT p.*,
                    count(DISTINCT l.id) as likes_count,
                    count(DISTINCT liked.id) as is_liked,
                    count(DISTINCT disliked.id) as is_disliked,
                    count(DISTINCT dl.id) as dislikes_count,
                    count(DISTINCT c.id) as comments_count,
                    u.fname, u.lname, u.last_action,u.id AS author 
                FROM ".$this->config['dbprefix']."posts p
                LEFT JOIN ".$this->config['dbprefix']."users u ON(p.author = u.id) 
                LEFT JOIN ".$this->config['dbprefix']."likes l ON(l.item = p.id AND l.type = 'post' AND l.value = 0) 
                LEFT JOIN ".$this->config['dbprefix']."likes liked ON(liked.item = p.id AND liked.type = 'post' AND liked.value = 0 AND liked.author = ".$this->userid.") 
                LEFT JOIN ".$this->config['dbprefix']."likes dl ON(dl.item = p.id AND dl.type = 'post' AND dl.value = 1) 
                LEFT JOIN ".$this->config['dbprefix']."likes disliked ON(disliked.item = p.id AND disliked.type = 'post' AND disliked.value = 1 AND disliked.author = ".$this->userid.") 
                LEFT JOIN ".$this->config['dbprefix']."comments c ON(c.item = p.id AND c.type = 'post' AND c.deleted = 0) 
                WHERE p.wall_id = '$id' AND p.type = '0' AND p.deleted = 0 
                GROUP BY p.id 
                ORDER BY p.date DESC 
                LIMIT ".$offset.",".$limit;*/

        $select = "p.*,
                    count(DISTINCT l.id) as likes_count,
                    count(DISTINCT liked.id) as is_liked,
                    count(DISTINCT disliked.id) as is_disliked,
                    count(DISTINCT dl.id) as dislikes_count,
                    count(DISTINCT c.id) as comments_count,
                    u.fname, u.lname, u.last_action,u.id AS author_id";

        $this->db->select($select)
        	->from('posts p')
        	->join('users u', 'p.author = u.id', 'left')
        	->join('likes l', "l.item = p.id AND l.type = 'post' AND l.value = 0", 'left')
        	->join('likes liked', "liked.item = p.id AND liked.type = 'post' AND liked.value = 0 AND liked.author = ".$this->user_id, 'left')
        	->join('likes dl', "dl.item = p.id AND dl.type = 'post' AND dl.value = 1", 'left')
        	->join('likes disliked', "disliked.item = p.id AND disliked.type = 'post' AND disliked.value = 1 AND disliked.author = ".$this->user_id, 'left')
        	->join('comments c', "c.item = p.id AND c.type = 'post' AND c.deleted = 0", 'left')
            ->group_by('p.id')
        	->order_by('p.date', 'desc')
        	->limit($limit, $offset);

        /*$this->db->query("SELECT p.*,
                    count(DISTINCT l.id) as likes_count,
                    count(DISTINCT liked.id) as is_liked,
                    count(DISTINCT disliked.id) as is_disliked,
                    count(DISTINCT dl.id) as dislikes_count,
                    count(DISTINCT c.id) as comments_count,
                    u.fname, u.lname, u.last_action,u.id AS author 
                FROM soc_posts p
                LEFT JOIN soc_users u ON(p.author = u.id) 
                LEFT JOIN soc_likes l ON(l.item = p.id AND l.type = 'post' AND l.value = 0) 
                LEFT JOIN soc_likes liked ON(liked.item = p.id AND liked.type = 'post' AND liked.value = 0 AND liked.author = ".$this->user_id.") 
                LEFT JOIN soc_likes dl ON(dl.item = p.id AND dl.type = 'post' AND dl.value = 1) 
                LEFT JOIN soc_likes disliked ON(disliked.item = p.id AND disliked.type = 'post' AND disliked.value = 1 AND disliked.author = ".$this->user_id.") 
                LEFT JOIN soc_comments c ON(c.item = p.id AND c.type = 'post' AND c.deleted = 0) 
                WHERE p.wall_id = '$id' AND p.type = '0' AND p.deleted = 0 
                GROUP BY p.id 
                ORDER BY p.date DESC 
                LIMIT ".$offset.",".$limit);*/

    	$query = $this->db->get();
        //dump($this->db->last_query());
    	return $query->result();
    }

    function getPost($id, $deleted = false){
        if(empty($this->userid))
            $this->userid = 0;

        $deleted = (!$deleted)?' AND p.deleted = 0 ':'';

        $sql = "SELECT p.*, count(DISTINCT l.id) as likes_count, 
                            count(DISTINCT liked.id) as is_liked, 
                            count(DISTINCT disliked.id) as is_disliked, 
                            count(DISTINCT dl.id) as dislikes_count, 
                            count(DISTINCT c.id) as comments_count,
                            u.fname, u.lname, u.last_action,u.id AS user_id 
                FROM ".$this->config['dbprefix']."posts p
                LEFT JOIN ".$this->config['dbprefix']."users u ON(p.author = u.id) 
                LEFT JOIN ".$this->config['dbprefix']."likes l ON(l.item = p.id AND l.type = 'post' AND l.value = 0) 
                LEFT JOIN ".$this->config['dbprefix']."likes liked ON(liked.item = p.id AND liked.type = 'post' AND liked.value = 0 AND liked.author = ".$this->userid.") 
                LEFT JOIN ".$this->config['dbprefix']."likes disliked ON(disliked.item = p.id AND disliked.type = 'post' AND disliked.value = 1 AND disliked.author = ".$this->userid.") 
                LEFT JOIN ".$this->config['dbprefix']."likes dl ON(dl.item = p.id AND dl.type = 'post' AND dl.value = 1) 
                LEFT JOIN ".$this->config['dbprefix']."comments c ON(c.item = p.id AND c.type = 'post' AND c.deleted = 0) 
                WHERE p.id = '$id' AND p.type = '0' $deleted
                GROUP BY p.id";
        
        $query = $this->db->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $result = $query->fetch();
        return $result;
    }

    function getPostComments($id, $limit = 5, $offset = 0){
        if(empty($this->userid))
            $this->userid = 0;

        /*$sql = "SELECT 
                FROM ".$this->config['dbprefix']." 
                LEFT JOIN ".$this->config['dbprefix']."users u ON(u.id = c.author) 
                LEFT JOIN ".$this->config['dbprefix']."comments ac ON(ac.id = c.answer) 
                LEFT JOIN ".$this->config['dbprefix']."users au ON(au.id = ac.author) 
                LEFT JOIN ".$this->config['dbprefix']."likes l ON(l.item = c.id AND l.type = 'comment' AND l.value = 0) 
                LEFT JOIN ".$this->config['dbprefix']."likes liked ON(liked.item = c.id AND liked.type = 'comment' AND liked.value = 0 AND liked.author = ".$this->userid.") 
                LEFT JOIN ".$this->config['dbprefix']."likes dl ON(dl.item = c.id AND dl.type = 'comment' AND dl.value = 1) 
                LEFT JOIN ".$this->config['dbprefix']."likes disliked ON(disliked.item = c.id AND disliked.type = 'comment' AND disliked.value = 1 AND disliked.author = ".$this->userid.") 
                WHERE c.item = $id AND c.type = 'post' AND c.deleted = 0
                GROUP BY c.id
                HAVING c.id <> '' 
                ORDER BY c.date ASC";*/

        $select = "c.*,u.fname,u.lname,u.last_action,u.id AS author,CONCAT(au.fname , ' ' , au.lname) as answer_name, 
                    count(DISTINCT l.id) as likes_count,
                    count(DISTINCT liked.id) as is_liked,
                    count(DISTINCT disliked.id) as is_disliked,
                    count(DISTINCT dl.id) as dislikes_count";

        $this->db->select($select)
            ->from('comments c')
            ->join('comments ac', "ac.id = c.answer", 'left')
            ->join('users u', 'u.id = c.author', 'left')
            ->join('users au', 'au.id = ac.author', 'left')
            ->join('likes l', "l.item = c.id AND l.type = 'comment' AND l.value = 0", 'left')
            ->join('likes liked', "liked.item = c.id AND liked.type = 'comment' AND liked.value = 0 AND liked.author = ".$this->userid, 'left')
            ->join('likes dl', "dl.item = c.id AND dl.type = 'comment' AND dl.value = 1", 'left')
            ->join('likes disliked', "disliked.item = c.id AND disliked.type = 'comment' AND disliked.value = 1 AND disliked.author = ".$this->userid, 'left')
            ->where("c.item = $id AND c.type = 'post' AND c.deleted = 0")
            ->group_by('c.id')
            ->having("c.id <> ''")
            ->order_by('c.date', 'asc')
            ->limit($limit, $offset);

        $query = $this->db->get();
        //dump($this->db->last_query());
        return $query->result();
    }
}