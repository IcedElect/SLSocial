<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Fields
{

    protected $aFields = array();
    private $CI;
    private $table;
    private $translabel_table = '';
    private $form_action = '';
    private $editAction = '/edit';
    private $editLink = '{$aConf.base_url}{$aConf.active_module}{$editAction}/\'+id+\'/';
    private $deleteAction = 'del';
    private $viewAction = 'getlist';
    private $use_one_page = false;
    private $redirect_after = '';
    private $lang = '';
    private $hook = array();
    private $nId = '';
    private $have_files = false;
    private $disable_cancel = false;
    private $submit_text = false;
    private $tabs = array(array('title' => 'Основаное', 'id' => 'main'));
    private $tabs_active = false;
    private $_messages = array(
        'wrong_id' => array(
            'type' => 'error',
            'key' => 'form_edit_wrong_id',
            'text' => 'Не верный id'),
        'edit_save' => array(
            'type' => 'success',
            'key' => 'form_edit_save',
            'text' => 'Данные обновленны'),
        'add_save' => array(
            'type' => 'success',
            'key' => 'form_add_save',
            'text' => 'Запись добавленна'),
    );
    private $hooks = array();
    private $hooks_names = array();
    private $default_field = array(
        'rules' => 'trim|required',
        'field' => '',
        'title' => '',
        'class' => 'form-control',
        'table_show' => 1,
        'table_width' => '',
        'table_align' => '',
        'form_show' => true,
        'translable' => false,
        'validate' => 1,
        'type' => '',
        'value' => '',
        'readonly' => false,
        'disabled' => false,
        'checked' => false,
        'multiple' => false,
        'options' => array(),
        'image_src' => '',
        'view_callback' => '',
        'id_as_name' => false,
        'prefix' => '',
        'comments' =>'',
        'custom_field_template' => '',
        'searchoptions' => "{sopt:['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc'] }",
        'search' => true,
        'sortable' => true,
        'sorttype' => 'string',
        'tab' => 0,
        'tag_name' => 'input',
        'error' => false,
        'attributes' => array(),
    );
    private $form_attributes = array(
        'id' => 'frm',
        'method' => 'post',
        'role' => 'form',
        'class' => 'row form-horizontal'
    );


    function __construct($no_db = false)
    {
        $this->CI =& get_instance();
        if(!$no_db){
            $this->lang = $this->CI->translate->get_current_lang();
            $this->messages = &$this->CI->messages;
        }
        $this->add_hook('after_save', array($this, 'run_custom_save'));

    }

    public function setFields($aFields)
    {
        $this->aFields = $aFields;
    }

    public function setTable($table_name, $translabel_table = '')
    {
        $this->table = $table_name;
        if ($translabel_table) {
            $this->translabel_table = $translabel_table;
        }
    }

    private function is_translabel()
    {
        return $this->translabel_table ? true : false;
    }

    public function setEditAction($action)
    {
        $this->editAction = $action;
    }

    public function setEditLink($link)
    {
        $this->editLink = $link;
    }

    public function setViewAction($action)
    {
        $this->viewAction = $action;
    }

    public function setDeleteAction($action)
    {
        $this->deleteAction = $action;
    }

    public function setDisableCancel($action = true)
    {
        $this->deleteAction = $action;
    }

    public function setSubmitText($text)
    {
        $this->CI->my_smarty->assign('submit_text', $text);
    }

    public function setRedirectAfter($redirect)
    {
        $this->redirect_after = $redirect;
    }

    public function setHook($place, $template, $vars = array())
    {
        foreach($vars as $key => $value){
            $this->CI->my_smarty->assign($key, $value);
        }

        if(!isset($this->hooks[$place]))
            $this->hooks[$place] = array();

        if($template == 'form_tags' || $template == 'form_actors'){
            $this->CI->frontend->add_js('assets/js/tagit/tag-it.min.js');
            $this->CI->frontend->add_js('assets/js/tagify/tagify.js');
            $this->CI->frontend->add_js('assets/js/chosen/chosen.jquery.min.js');
            $this->CI->frontend->add_js('assets/js/tags.js');
            $this->CI->frontend->add_css('assets/js/tagit/jquery.tagit.css');
            $this->CI->frontend->add_css('assets/js/tagify/tagify.css');
            $this->CI->frontend->add_css('assets/js/chosen/chosen.min.css');
        }
        if($template == 'form_tags'){
            $this->CI->my_smarty->assign('tags', $this->get_all_tags());
        }
        if($template == 'form_actors'){
            $this->CI->my_smarty->assign('actors', $this->get_all_actors());
        }
        if($template == 'form_categories'){
            $this->CI->my_smarty->assign('categories', $this->get_all_categories());
        }
        $this->hooks_names[$template] = $place;
        $this->hooks[$place][] = $template;
    }

    public function useOnePage()
    {
        $this->use_one_page = true;
    }

    public function disableAdd()
    {
        $this->CI->my_smarty->assign('disable_add', 1);
    }
    public function addTab($arr = false){
        if($arr)
            $this->tabs[] = $arr;
        $this->tabs_active = true;
    }

    public function init_model()
    {
        if (!$this->is_translabel()) {
            $this->CI->load->model('default_model', 'jqgrid_default_model');
            $this->default_model = &$this->CI->jqgrid_default_model;
            $this->default_model->setTable($this->table);
        } else {
            $this->CI->load->model('default_translabel_model', 'jqgrid_default_translabel_model');
            $this->default_model = &$this->CI->jqgrid_default_translabel_model;
            $this->default_model->setTable($this->table, $this->translabel_table);
            $this->default_model->setLang($this->lang);
        }
    }

    public function pagination_config($aData){
        $config = array();
        $config["base_url"] = base_url() . $this->CI->frontend->aConf['active_module'];
        $config["total_rows"] = $aData->records;
        $config["per_page"] = $aData->limit;
        $config["uri_segment"] = 3;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['attributes'] = array('class' => 'btn');
        $config['cur_tag_open'] = '<span class="btn">';
        $config['cur_tag_close'] = '</span>';

        $query_string = $_GET;
        if (isset($query_string['page']))
            unset($query_string['page']);

        if (count($query_string) > 0){
            $config['suffix'] = '&' . http_build_query($query_string, '', "&");
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_string, '', "&");
        }

        return $config;
    }

    public function jqgrid($view = true)
    {
        $this->CI->my_smarty->assign('aFields', $this->aFields);
        $this->CI->my_smarty->assign('use_one_page', $this->use_one_page);
        $this->CI->my_smarty->assign('viewAction', $this->viewAction);
        $this->CI->my_smarty->assign('deleteAction', $this->deleteAction);
        $this->CI->my_smarty->assign('editAction', $this->editAction);
        $this->CI->my_smarty->assign('editLink', $this->editLink);
        if($view)
            $this->CI->frontend->view('default_grid');
    }

    public function jqgrid_getlist($filter = false)
    {
        $this->init_model();
        $sidx = $this->CI->input->get('sort');
        $page = $this->CI->input->get('page')+1;
        if(!$page)
            $page = 1;
        // get how many rows we want to have into the grid
        $aFilter['filters'] = (object) array(
            'rules' => array(),
            'groupOp' => 'AND'
        );
        if($filter)
            $aFilter['filters']->rules[] = (object) $filter;

        $search = $this->CI->input->get('search');
        if(!$search)
            $search = false;
        $aFilter['fields'] = array();

        $limit = $this->CI->input->get('rows');
        if(!$limit)
            $limit = 20;

        $sord = $this->CI->input->get('sort_type');
        if(!$sord)
            $sord = 'desc';

        if ($search) {
            if(!$sidx)
                $sidx = 'title';

            $aFilter['filters']->rules[] = (object) array('op' => 'cn', 'field' => $sidx, 'data' => $search);
            $count = count($this->default_model->getDatabyFilter($aFilter, $sidx, $sord));
        } else {
            $count = $this->default_model->getDataCountbyFilter();
        }

        if(!$sidx)
            $sidx = 'id';

        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page = $total_pages;
        $start = $limit * $page - $limit; // do not put $limit*($page - 1)
        if ($start < 0) $start = 0;

        $response = new StdClass;
        $response->page = $page;
        $response->total = $total_pages;
        $response->limit = $limit;
        $response->records = $count;
        $response->offset = $start;
        $response->rows = array();

        $response->sort = $sidx;
        $response->sort_type = $sord;
        $response->search = $search;

        $aFilter['limit'] = $limit;
        $aFilter['offset'] = $start;

        $_rows = $this->default_model->getDatabyFilter($aFilter, $sidx, $sord);

        foreach ($_rows as $key => $value) {
            $row['id'] = $value->id;
            $row['cell'] = array();
            foreach ($this->aFields as $key => $field) {
                $_value = !isset($value->{$field['field']}) ? '' : $value->{$field['field']};
                if ($field['table_show'] && empty($field['view_callback'])) {
                    $row['cell'][$field['field']] = $_value;
                }
                if (!empty($field['view_callback'])) {
                    if (empty($_value)) {
                        $row['cell'][$field['field']] = call_user_func($field['view_callback'], $value, '', $field);
                    } else {
                        $row['cell'][$field['field']] = call_user_func($field['view_callback'], $value, $_value, $field);
                    }
                }
            }

            $response->rows[] = $row;
        }

        return $response;
    }

    public function grid($left = false, $filter = false){
        $this->tabs_active = false;
        $aData = $this->jqgrid_getlist($filter);

        $this->CI->load->library('pagination');
        $config = $this->pagination_config($aData);
        $this->CI->pagination->initialize($config);
        if(!$left)
            $left = array(
                'buttons' => array(
                    array('text' => 'Добавить запись', 'url' => base_url() . $this->CI->frontend->aConf['active_module'] . $this->editAction)
                )
            );

        $this->CI->my_smarty->assign('left', $left);
        $this->CI->my_smarty->assign('aData', $aData);
        $this->CI->my_smarty->assign('pagination', $this->CI->pagination->create_links());

        $this->jqgrid(false);
        $this->CI->frontend->view('default_list_page');
    }

    public function form($fp_nId = '', $redirect_to = true)
    {
        if (!empty($fp_nId)) {
            $this->nId = $fp_nId;
            $action = 'edit';
            $title = 'Редактирование ';
        } else {
            $action = 'add';
            $title = 'Создание ';
        }
        $moduleTitle = $this->CI->frontend->moduleTitle;
        //$this->CI->frontend->setTitle($title.' '.$moduleTitle);
        $this->CI->frontend->moduleTitle = $moduleTitle;

        $this->CI->frontend->aConf_add('backButton', true);
        $this->CI->frontend->messages_custom_place();

        $this->input = &$this->CI->input;
        $this->init_model();

        $destination = $this->input->get_post('destination');
        $aData = array('categories' => array());
        if ($action === 'edit') {
            $aData = $this->default_model->getDataById($fp_nId);
            if (empty($aData)) {
                $this->message('wrong_id');
            }
            if(isset($this->hooks_names['form_categories']))
                $aData['categories'] = $this->default_model->get_categories($this->table, $fp_nId);

            if(isset($this->hooks_names['form_tags']))
                $aData['tags'] = $this->default_model->get_tags($this->table, $fp_nId);
            if(isset($this->hooks_names['form_actors']))
                $aData['actors'] = $this->default_model->get_tags($this->table, $fp_nId, 'actor');
            if(isset($this->hooks_names['screenshots']))
                $aData['screenshots'] = $this->default_model->get_screenshots($this->table, $fp_nId);
        }
        if ($this->input->post('submit')) {
            $is_validate = $this->validate();

            $this->add_hook('after_save', array($this, 'save'));

            $_data = $this->get_fields_data($aData);
            $oldData = $aData;
            $aData = $_data['aData'];
            $aTransData = $_data['aTransData'];
            if ($is_validate) {
                if (!$this->is_translabel()) {
                    $id = $this->default_model->save($aData, $action, $fp_nId);
                } else {
                    $id = $this->default_model->save($aData, $aTransData, $action, $fp_nId);
                }
                if ($id) {
                    $hook_vars = array('id' => $id, 'oldData' => $oldData, 'aData' => $aData, 'aSysData' => $_data);
                    $this->run_hook('after_save', $hook_vars);
                    if ($action === 'edit') {
                        $this->message('edit_save');
                        $this->run_hook('after_save_edit', $hook_vars);
                        if (!empty($this->redirect_after)) {
                            mygoto($this->redirect_after);
                        }
                    } else {
                        $this->message('add_save');
                        $this->run_hook('after_save_add', $hook_vars);
                        //redirect to edit page
                            mygoto('/'.$this->CI->frontend->aConf['active_module'] . $this->editAction . '/' . $id);
                    }
                }
            }
            if ($this->is_translabel()) {
                $aData = array_merge($aData, $aTransData);
            }
            if(isset($this->hooks_names['form_tags']))
                $aData['tags'] = $this->default_model->get_tags($this->table, $fp_nId);
            if(isset($this->hooks_names['form_actors']))
                $aData['actors'] = $this->default_model->get_tags($this->table, $fp_nId, 'actor');
            if(isset($this->hooks_names['form_categories']))
                $aData['categories'] = $this->default_model->get_categories($this->table, $fp_nId);
        }

        $this->CI->my_smarty->assign('form_attributes', $this->form_attributes);
        $this->CI->my_smarty->assign('destination', $destination);
        $this->CI->my_smarty->assign('nId', $fp_nId);
        $this->CI->my_smarty->assign('aData', $aData);
        $this->CI->my_smarty->assign('action', $action);
        $this->CI->my_smarty->assign('editAction', $this->editAction);
        $this->CI->my_smarty->assign('aFields', $this->aFields);
        $this->CI->my_smarty->assign('hooks', $this->hooks);
        $this->CI->my_smarty->assign('tabs', $this->tabs);
        $this->CI->frontend->view('default_form');
    }

    function setAction($url){
        $this->form_action = $url;
    }

    function getForm(){
        $this->input = &$this->CI->input;

        if ($this->input->post('submit')) {
            $is_validate = $this->validate();
            if($is_validate){
                $hook_vars = array();
                $this->run_hook('after_save', $hook_vars);
            }
        }
        $this->CI->frontend->aConf_add('backButton', true);
        return array(
            'fields' => $this->aFields,
            'buttons' => true,
            'form_action'  => $this->form_action,
        );
    }

    public function get_fields_data($oldData = array()){
        $aData = $aTransData = array();
        $custom_save = array();
        foreach ($this->aFields as $key => $fields) {
            foreach($fields as $key => $field){
                if (!isset($field['form_disable']) && empty($field['disable_save']) && empty($field['custom_field_save'])) {
                    if ($field['validate']) {
                        $value = $this->CI->validation->{$field['field']};
                    } else {
                        $value = $this->CI->input->get_post($field['field']);
                    }

                    if ($field['type'] != 'image') {
                        if (!$this->is_translabel()) {
                            $aData[$field['field']] = $value;
                        } else {
                            if ($field['translable']) {//translate_prefix
                                $aTransData[$field['field']] = $value;
                            } else {
                                $aData[$field['field']] = $value;
                            }
                        }
                    }
                    if ($field['type'] == "checkbox") {
                        if (!isset($aData[$field['field']])) {
                            $aData[$field['field']] = 0;
                        }
                    }

                    if ($field['type'] == 'image') {
                        if (!empty($_FILES[$field['field']]['tmp_name'])){
                            if ($field['id_as_name']){
                                $file_name = empty($this->vote['id']) ? uniqid() : $this->vote['id'];
                                $field['image_config']['file_name'] = $file_name;
                            }
                            $this->CI->uploader->set_upload_config($field['image_config']);
                            $this->CI->uploader->set_field_title($field['title']);
                            $image_data = $this->CI->uploader->run($field['field']);
                            if ($image_data['error']) {
                                $is_validate = false;
                                $this->CI->messages->add_tmessages_list('error', $image_data['data'], $field['field'] . 'upload');
                            } else {
                                if (!empty($oldData[$field['field']]) && $oldData[$field['field']] != $image_data['data']['file_name']) {
                                    @unlink($field['image_src'] . $oldData[$field['field']]);
                                }
                                $aData[$field['field']] = $image_data['data']['file_name'];
                            }
                        } elseif(!empty($oldData[$field['field']])) {
                            $aData[$field['field']] = $oldData[$field['field']];
                        }
                    }

                }
                if (!empty($field['custom_field_save'])){
                    $custom_save[] = array('callback' => $field['custom_field_save'], 'field' => $field);
                }
            }
        }

        return array(
            'aData' => $aData,
            'aTransData' => $aTransData,
            'saveCallbackList' => $custom_save,
        );
    }

    function message($key)
    {
        if ($key != 'add_save') {
            $this->messages->add($this->_messages[$key]['type'],
                $this->_messages[$key]['key'],
                $this->_messages[$key]['text']);
        } else {
            $this->messages->add_message_next_time($this->_messages[$key]['type'],
                $this->_messages[$key]['key'],
                $this->_messages[$key]['text']);
        }
    }

    public function setMessage($form_key, $key, $text)
    {
        $this->_messages[$form_key] = array(
            'type' => $this->_messages[$form_key]['type'],
            'key' => $key,
            'text' => $text
        );
    }

    function get_all_categories(){
        $this->CI->load->model('news_model');
        $this->CI->news_model->setTable('videos_categories');
        return $this->CI->news_model->getCategoriesTree();
    }
    function save_categories($item)
    {
        $categories_list = $this->CI->input->get_post('categories');

        if($this->default_model->save_categories($this->table, $item['id'], $categories_list))
            return $categories_list;
    }

    function save_screenshots($item = ''){
        if(empty($_FILES['screenshots']['name'][0]))
            return;
        $this->default_model->setTable($this->table);
        $this->CI->load->helper('file_size');
        
        $userfile = 'screenshots';
        $image_path = '/userfiles/uploads/'.$this->table.'/';
        $allowed = 'gif|jpg|png';
        $allowed = '*';
        $max_size = file_upload_max_size();

        $output = array();
        $this->CI->load->library('upload');
        if(!is_dir('.'.$image_path))
        {
            mkdir('.'.$image_path);
        }
        $files = $_FILES;
        $files_obj = array();
        $cpt = count($_FILES[$userfile]['name']);
        for($i=0; $i<$cpt; $i++)
        {
           if($files[$userfile]['tmp_name'][$i]!='')
           {
                $_FILES[$userfile]['name']= $files[$userfile]['name'][$i];
                $_FILES[$userfile]['type']= $files[$userfile]['type'][$i];
                $_FILES[$userfile]['tmp_name']= $files[$userfile]['tmp_name'][$i];
                $_FILES[$userfile]['error']= $files[$userfile]['error'][$i];
                $_FILES[$userfile]['size']= $files[$userfile]['size'][$i];    

                $config['upload_path'] = '.'.$image_path;
                $config['allowed_types'] = $allowed;
                $config['max_size'] = $max_size;
                // if want to rename file
                $random_digit = rand(00, 999999);
                $img=$files[$userfile]['name'][$i];
                $ext = explode(".", $img);
                $file_name=$random_digit.'.'.$ext[1];
                $config['file_name'] = $file_name;
                // end renaming
                $this->CI->upload->initialize($config);
                if(!$this->CI->upload->do_upload($userfile)){
                    $error = $this->CI->upload->display_errors();
                    $output = array('error' => $error[0]);
                }
                $newfile[]=$this->CI->upload->file_name;
                $files_obj[] = array($this->table.'_id' => $item['id'], 'image_path' => $image_path.$config['file_name'], 'size' => $_FILES[$userfile]['size'], 'weight' => $i, 'main' => 0);
           }
        }
        if($this->default_model->save_screenshots($this->table, $item['id'], $files_obj));
        echo json_encode($output);
    }

    function save($item){
        if(isset($this->hooks_names['form_actors'])){
            $this->save_tags($item, false, 'actor');
        }
        if(isset($this->hooks_names['form_categories'])){
            $this->save_categories($item);
        }
        if(isset($this->hooks_names['form_tags'])){
            $this->save_tags($item);
        }
        if(isset($this->hooks_names['screenshots'])){
            $this->save_screenshots($item);
        }
    }

    function save_tags($item, $allow_new = true, $table = 'tag')
    {
        $tags = $this->CI->input->get_post($table);
        if(!empty($tags)){
            if(!is_array($tags))
                return;
            $tag_list = $this->default_model->get_tags_id($tags, $table);
            if($allow_new){
                if (count($tags) != count($tag_list)) {
                    $need_save = array_udiff($tags, array_values($tag_list), array($this, 'strcase'));
                    if ($need_save) {
                        $new_tags = $this->default_model->add_tags($need_save, $table);

                        $tag_list = $tag_list + $new_tags;
                    }
                }
            }
        }else{
            $tag_list = null;
        }
        $this->default_model->save_tags($this->table, $item['id'], $tag_list, $table);
        //$this->vote['tags'] = $tag_list;
    }
    function strcase($a, $b)
    {
        $a = mb_strtoupper($a);
        $b = mb_strtoupper($b);
        if ($a === $b) {
            return 0;
        }
        return ($a > $b) ? 1 : -1;
    }
    function get_all_actors(){
        $this->CI->load->model('default_model', 'actors_model');
        $this->CI->actors_model->setTable('actor');
        $_actors = $this->CI->actors_model->getAllData();
        $aTags = array();
        foreach ($_actors as $key => $value) {
            $aTags[] = $value->name;
        }
        return json_encode($aTags);
    }
    function get_all_tags($table = 'tag'){
        $this->CI->load->model('default_model', 'tags_model');
        $this->CI->tags_model->setTable($table);
        $_tags = $this->CI->tags_model->getAllData();
        $aTags = array();
        foreach ($_tags as $key => $value) {
            $aTags[] = $value->name;
        }
        return json_encode($aTags);
    }

    public function validate()
    {
        $this->CI->load->library('validation');
        $this->CI->validation->addClassObject($this);

        $aValRules = $this->getValidationFields();

        $this->CI->validation->set_fields($aValRules['fields']);
        $this->CI->validation->set_rules($aValRules['rules']);

        if ($this->CI->validation->run() == FALSE) {
            $aErrors = $this->CI->validation->get_errors_array();
            $aFields = array_keys($aErrors);
            foreach($this->aFields as $tab => $fields){
                foreach($fields as $key => $field){
                    if(in_array($field['field'], $aFields)){
                        $this->aFields[$tab][$key]['error'] = true;
                    }
                }
            }
            $this->CI->messages->add_tmessages_list('error', $aErrors, 'validation');
            return false;
            return $this->CI->messages->get_all_messages();
        } else {
            return true;
        }
    }

    private function getValidationFields()
    {
        $result = array();
        foreach ($this->aFields as $key => $value) {
            if($this->tabs_active){
                foreach($value as $key => $value){
                    if ($value['validate'] && empty($value['disable_save'])) {
                        $result['rules'][$value['field']] = $value['rules'];
                        $result['fields'][$value['field']] = $value['title'];
                    }
                }
            }else{
                if ($value['validate'] && empty($value['disable_save'])) {
                    $result['rules'][$value['field']] = $value['rules'];
                    $result['fields'][$value['field']] = $value['title'];
                }
            }
        }
        return $result;
    }

    function del()
    {
        $action = $this->CI->input->get_post('oper');
        if ($action == 'del') {
            $this->CI->load->library('validation');
            $rules = array(
                'id' => 'trim|required'
            );
            $fields = array(
                'id' => 'ID'
            );
            $this->CI->validation->set_fields($fields);
            $this->CI->validation->set_rules($rules);

            if ($this->CI->validation->run() == FALSE) {
                $aResponse = array(false, $this->CI->validation->error_string, '');
                $this->CI->frontend->returnJson($aResponse);
                return false;
            } else {
                $this->init_model();
                $sId = $this->CI->validation->id;
                $aId = explode(',', $sId);
                if ($this->have_files) {
                    $this->remove_files($aId);
                }
                $this->default_model->del($aId);
            }
        }
        $this->CI->frontend->returnJson(array('success' => true));
    }

    private function remove_files($aId)
    {
        $rows = $this->default_model->getDataByIdList($aId);
        foreach ($this->aFields as $key => $field) {
            if ($field['type'] == 'image' || $field['type'] == 'file') {
                foreach ($rows as $key => $row) {
                    @unlink($field['image_src'] . $row->$field['field']);
                }
            }
        }
    }

    function remove_field_file($id, $field_name)
    {
        $this->init_model();
        $row = $this->default_model->getDataById($id);
        $this->default_model->update(array($field_name => ''), array('id' => $id));
        @unlink($this->aFields[$field_name]['image_src'] . $row[$field_name]);
        $responce = new StdClass;
        $responce->success = true;
        $this->CI->frontend->returnJson($responce);
    }


    public function check_view($tag){
        return '<input type="checkbox">';
    }

    public function addField_check($params = array())
    {
        $default = array(
            'rules' => '',
            'field' => 'check',
            'title' => $this->CI->translate->t('field_check', ''),
            'table_width' => 30,
            'table_align' => 'center',
            'validate' => 0,
            'type' => 'static',
            'disable_save' => 1,
            'form_disable' => true,
            'sortable' => false,
            'view_callback' => array($this, 'check_view'),
        );

        $result = array_merge($default, $params);
        $this->addField($result);
    }
    public function addField_id($params = array())
    {
        $default = array(
            'rules' => '',
            'field' => 'id',
            'class' => 'form-control-static',
            'title' => $this->CI->translate->t('field_id', 'ID'),
            'table_width' => 50,
            'table_align' => 'center',
            'validate' => 0,
            'type' => 'static',
            'disable_save' => 1,
            'searchoptions' => "{sopt:['eq','ne','le','lt','gt','ge'] }",
            'sorttype' => 'integer',
            'tag_name' => 'p',
        );

        $result = array_merge($default, $params);
        $this->addField($result);
    }


    public function addField_text($params)
    {
        $default = array(
            'type' => 'text',
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }
    public function addField_hidden($params)
    {
        $default = array(
            'type' => 'hidden',
            'table_show' => false,
            'form_show' => false,
            'form_disable' => true,
            'validate' => false,
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }
    public function addField_static($params)
    {
        $default = array(
            'type' => 'static',
            'class' => 'form-control-static',
            'rules' => '',
            'tag_name' => 'p',
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }

    public function addField_textarea($params)
    {
        $default = array(
            'type' => 'textarea',
            'search' => false,
            'tag_name' => 'textarea',
            'value' => '',
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }

    public function addField_select($params)
    {

        $select_values = '';
        if (!empty($params['options'])){
            $select_values = array();
            foreach ($params['options'] as $key => $value){
                $select_values[] = $key . ':' . $value;
            }
            $select_values = implode(';', $select_values);
        }
        $default = array(
            'type' => 'select',
            'view_callback' => array($this, 'select_view_callback'),
            'searchoptions' => '{
             sopt:["eq"],
             value: "' . $select_values . '",
            defaultValue: "-1"}',
            'tag_name' => 'select',
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }

    public function addField_checkbox($params)
    {
        $default = array(
            'validate' => 0,
            'type' => 'checkbox',
            'rules' => 'trim',
            'searchoptions' => '{
            sopt:["eq"],
            value: "1:' . $this->CI->translate->t('field_checkbox_on', 'вкл') .
                  ';0:' . $this->CI->translate->t('field_checkbox_off', 'выкл') . '",
            defaultValue: "-1"}',

        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }

    public function addField_date($params)
    {
        $default = array(
            'validate' => 0,
            'type' => 'text',
            'view_callback' => array($this, 'date_view_callback'),
        );
        $result = array_merge($default, $params);
        $this->addField($result);
    }

    public function addField($params)
    {
        $result = array_merge($this->default_field, $params);
        $attributes = array(
            'type' => $result['type'],
            'name' => $result['field'],
            'value' => $result['value'],
            'class' => $result['class'],
            'readonly' => $result['readonly'],
            'disabled' => $result['disabled'],
            'checked' => $result['checked'],
            'multiple' => $result['multiple'],
        );
        $result['attributes'] = array_merge($result['attributes'], $attributes);
        if($this->tabs_active){
            if(!isset($this->aFields[$result['tab']]))
                $this->aFields[$result['tab']] = array();
            $this->aFields[$result['tab']][$result['field']] = $result;
            return true;
        }

        $this->aFields[$result['field']] = $result;
    }

    public function addField_wysiwyg($params)
    {
        $default = array(
            'type' => 'wysiwyg',
            'table_show' => false,
            'search' => false,
            'tag_name' => 'textarea',
            'value' => '',
        );
        $result = array_merge($default, $params);
        $this->addField($result);
        $this->CI->frontend->add_js('assets/plugins/tinymce/tinymce.min.js');
        $this->CI->frontend->add_js('assets/plugins/tinymce/themes/modern/theme.min.js');
        $this->CI->frontend->add_js('assets/plugins/tinymce/skins/lightgray/theme.min.js');
        $this->CI->frontend->add_js('assets/plugins/tinymce/skins/lightgray/skin.min.css');
        $this->CI->frontend->add_js('assets/plugins/tinymce/skins/lightgray/content.min.css');
        $this->CI->frontend->add_js('assets/plugins/tinymce/skins/lightgray/content.inline.min.css');
    }

    public function addField_image($params, $image_config = array())
    {
        $default = array(
            'type' => 'file',
            'validate' => 0,
            'view_callback' => array($this, 'image_view_callback'),
            'image_config' => $image_config,
            'search' => false,
        );
        if($params['multiple'] == true){
            $default['class'] = 'file file-loading';
            if($params['field'] == 'screenshots[]')
                $this->hooks_names['screenshots'] = 'photo';
            $default['attributes']['data-show-upload'] = "false";
            $this->CI->frontend->add_js('assets/js/fileInput/js/fileinput.js');
            $this->CI->frontend->add_js('assets/js/fileInput/js/plugins/sortable.js');
            $this->CI->frontend->add_js('assets/js/fileInput/themes/explorer/theme.js');
            $this->CI->frontend->add_css('assets/js/fileInput/css/fileinput.css');
            $this->CI->frontend->add_css('assets/js/fileInput/themes/explorer/theme.css');
        }
        $result = array_merge($default, $params);
        $this->addField($result);
        $this->have_files = true;
        $this->form_attributes['enctype'] = "multipart/form-data";
        $this->CI->load->library('Uploader');
    }

    function date_view_callback($row, $field)
    {
        return $this->CI->date_helper->getDateFormatted(strtotime($field));
    }

    function select_view_callback($row, $value, $field)
    {
        if (!empty($field['options'][$value])){
            return $field['options'][$value];
        } else {
            return $value;
        }
    }

    function one_word($data)
    {
        if (preg_match('/\s/', $data)) {
            $this->CI->validation->set_message('one_word', $this->translate->t('validation_one_word', 'Поле %s должно быть без пробелов.'));
            return false;
        }

        return true;
    }

    function unique($data, $field)
    {
        if ($this->default_model->check_duplicate(array('id' => $this->nId, $field => $data))) {
            $this->CI->validation->set_message('unique', $this->CI->translate->t('validation_is_unique', '%s уже существует.'));
            return false;
        }

        return true;
    }

    public function add_hook($name, $function)
    {
        $this->hook[$name] = $function;
    }

    private function run_hook($name, $params)
    {
        if (!empty($this->hook[$name])) {
            call_user_func($this->hook[$name], $params);
        }
    }

    function image_view_callback($row, $value, $field)
    {
        if (!empty($value)) {
            return '<img class="jqgrid_image" src="/' . $field['image_src'] . $value . '">';
        } else {
            return '';
        }
    }

    function run_custom_save($fp_params){
        if (!empty($fp_params['aSysData']['saveCallbackList'])){
            foreach($fp_params['aSysData']['saveCallbackList'] as $callback){
                call_user_func($callback['callback'], $fp_params, $callback['field']);
            }
        }
    }

    function assign_fields(){
        $this->CI->my_smarty->assign('aFields', $this->aFields);
    }

}
