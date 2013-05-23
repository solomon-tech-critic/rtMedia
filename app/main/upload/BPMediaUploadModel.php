<?php

/**
 * Description of BPMediaUploadModel
 *
 * @author Joshua Abenazer <joshua.abenazer@rtcamp.com>
 */
class BPMediaUploadModel {
    public $upload = array(
        'mode' => 'file_upload',
        'context' => false,
        'context_id' => false,
        'privacy' => 0,
        'custom_fields' => array(),
        'taxonomy' => array(),
        'album_id' => false
    );
    
    function set_post_object() {
        $this->upload = wp_parse_args($_POST, $this->upload);
        $this->sanitize_object();
        return $this->upload;
    }

    function has_context() {
        if (!isset($this->upload['context_id']))
            return false;
    }

    function set_context() {
        if (class_exists('BuddyPress')) {
            $this->set_bp_context();
        } else {
            $this->set_wp_context();
        }
    }

    function set_wp_context() {
        global $post;
        $this->upload['context'] = $post->post_type;
        $this->upload['context_id'] = $post->ID;
    }

    function set_bp_context() {
        if (bp_is_blog_page()) {
            $this->set_wp_context();
        } else {
            $this->set_bp_component_context();
        }
    }

    function set_bp_component_context() {
        $this->upload['context'] = bp_current_component();
        $this->upload['context_id'] = $this->get_current_bp_component_id();
    }

    function get_current_bp_component_id() {
        switch (bp_current_component()) {
            case 'groups': return bp_get_current_group_id();
                break;
            default:
                return bp_loggedin_user_id();
                break;
        }
    }

    function sanitize_object() {
        if (!$this->has_context())
            $this->set_context();
        
        if (!is_array($this->upload['taxonomy']))
            $this->upload['taxonomy'] = array($this->upload['taxonomy']);
        
        if (!is_array($this->upload['custom_fields']))
            $this->upload['custom_fields'] = array($this->upload['custom_fields']);
        
//        if ( !$this->has_album_id() )
//            $this->set_album_id();
    }
    
//    function has_album_id(){
//        if(!$this->upload['album_id'])
//            return false;
//        return true;
//    }
//    
//    function set_album_id(){
//        if (class_exists('BuddyPress')) {
//            $this->set_bp_album_id();
//        } else {
//            $this->set_wp_album_id();
//        }
//    }
//
//    function set_bp_album_id(){
//        if (bp_is_blog_page()) {
//            $this->set_wp_album_id();
//        } else {
//            $this->set_bp_component_album_id();
//        }
//    }
//
//    function set_wp_album_id(){
//        
//    }
//    
//    function set_bp_component_album_id() {
//        switch (bp_current_component()) {
//            case 'groups': return bp_get_current_group_id();
//                break;
//            default:
//                return bp_loggedin_user_id();
//                break;
//        }
//    }
}

?>
