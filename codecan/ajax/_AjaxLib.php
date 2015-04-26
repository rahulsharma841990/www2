<?php

class _iKeypass_Ajax
{
	private $min_lib;
	
	private $_type;
	
	private $_doc			=	"$(document)";
	
	private $_ajax			=	"$.ajax";
	
	private $_result_p		=	"result";
	
	private $_fun_param		=	"e";
	
	private $_error_param	=	"error";
	
	function __construct($type=NULL)
	{
		$this->min_lib="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js";
		if(!empty($type)){
			$this->_type=$type;
		}else{
			$this->_type="text/javascript";
		}
	}
	
	public function init()
	{
		$script=$this->open($this->min_lib);
		$script .=$this->close();
		echo $script;
		
	}
	
	public function open($src=NULL)
	{
		$script="<script";
		$script .=' type="'.$this->_type;
		if(!empty($src)){
			$script .='" src="'.$src;
		}
		$script .='">';
		echo $script;
	}
	
	public function close()
	{
		$script_close="</script>";
		echo $script_close;
	}
	
	public function set_ajax($post_url=NULL, $fields=array(), $succ_cont=NULL, $error_cont=NULL, $post_type="POST", $async='FALSE')
	{
		$ajax  =$this->_ajax;
		$ajax .="({";
		$ajax .=$this->_set_ajax_data($this->_result_p,$post_type,$post_url,$fields,$async,$succ_cont,$error_cont,$this->_error_param);
		$ajax .="});";
		$result=$this->document_ready($this->_fun_param,$ajax);
		echo $result;
	}
	
	private function _set_ajax_data($result_param,$post_type,$url,$fields,$async,$succ_cont,$error_cont,$error_param)
	{
		if(is_array($fields)){
			
			$am_per=1;
			$field="";
			foreach($fields as $key=>$value){
				if($am_per==1){
					$field .=$key."=".$value;
					$am_per++;
				}else{
					$field .="&".$key."=".$value;
				}
			}
		}
		$ajax_data  ='type:"'.$post_type.'",';
		$ajax_data .='url:"'.$url.'",';
		$ajax_data .='data:"'.$field.'",';
		$ajax_data .='async:"'.$async.'",';
		$ajax_data .='success:function(';
		$ajax_data .=$result_param;
		$ajax_data .='){';
		$ajax_data .=$succ_cont;
		$ajax_data .='},';
		if($error_cont!=NULL){
			$ajax_data .='error:function(';
			$ajax_data .=$error_param;
			$ajax_data .='){';
			$ajax_data .=$error_cont;
			$ajax_data .='}';
		}
		return $ajax_data;
	}
	
	public function document_ready($fun_param=NULL,$content=NULL)
	{
		$script=$this->_doc;
		$script .=".ready";
		$script .="(function(".$fun_param."){";
		$script .=$content;
		$script .="});";
		echo $script;
	}
	
	public function alert($param=NULL,$return_type=NULL)
	{
		$script="alert";
		$script .="('";
		$script .=$param;
		$script .="');";
		if($return_type==true){
			return $script;
		}else{
			echo $script;
		}
	}
}

?>