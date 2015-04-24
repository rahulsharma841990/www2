<?php
/**
 * Copyright 2014 iKeyPass, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * iKeyPass.
 *
 * As with any software that integrates with the iKeyPass platform, your use
 * of this software is subject to the iKeyPass Developer Principles and
 * Policies.
 
w * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
 
 
 /* Include Main PHP CURL File and Defined Variable */
 
 	define('_ikeyPass_Curl','_ikeyPassCurlHelper.php');
	
	/* Include Ajax Class for Run Ajax */
	
	define('_ikeyPass_Ajax','_iKeypass_Ajax.php');
	
 /* Include Sanitize Class For Security */
	
	define('_Sanitize','_ikeyPassSanitize.php');
	
 /* Required ikeyPass CURL Class*/

	require_once _ikeyPass_Curl;

 /* Required IkeyPass Ajax */
 
 	require_once _ikeyPass_Ajax;
	
 /* Required ikeyPass Sanitize Class*/
	
	require_once _Sanitize;
	
	
	class _ikeypass //extends _ikeyPassCurlHelper
	{
		/* Store the Secet Key, Which is Entered by User */
		
		private $secret_key;
		
		/* Store the User Signature, Which is Entered by User */
		
		private $signature;
		
		/* Store the Method Name, Which one User Want's to Call */
		
		private $method;
		
		/* Store Object of Sanitize Class */
		
		private $_Sanitize;
		
		/* Will Store the API Source Address */
		
		private $_Source;
		
		/* Create Object of CURL Class */
		
		private $_api;
		
		/* Object of Ajax Class */
		
		private $_ajax;
		
		/* Class Constructor */
		
		/*
		@Params MIXED $secret_key
		@Params MIXED $signature
		@Params API Source
		*/
		function __construct($secret_key,$signature,$source='http://abisyscorp.com/ik_api/_StockApi.php')
		{
			
			/* Create Object of Sanitize Class */
			
			$this->_Sanitize = new Sanitize();
			
			/* Assign the Secret Key to Private Variable */
			
			$this->secret_key = $this->_Sanitize->sanitize($secret_key);
			
			/* Assign the Signature to Private Variable */
			
			$this->signature = $this->_Sanitize->sanitize($signature);
			
			/* Assign Source Address to Private Variable */
			
			$this->_Source = $this->_Sanitize->sanitize($source);
			
			/* Create Object of CURL Class */
			
			$this->_api = new _ikeyPassCurlHelper();
			
			/* Create Object of Ajax Class */
			
			$this->_ajax= new _iKeypass_Ajax();

			$this->_Authorized_USER($secret_key,$signature,$link='http://abisyscorp.com/ik_api/_iKeyPass_Authorize.php');
		}
		
		
		private function _Authorized_USER($secret_key,$signature,$link)
		{
			$fields=array();
			$fields['secret_key']=$secret_key;
			$fields['signature']=$signature;
			$this->_api->init();
			$this->_api->setopt_array($this->_api_options($fields,$link));
			$data=$this->_api->exec();
			//echo $data;
			//echo $this->_api->error();
			$this->_api->close();
			$json=explode('text/html',$data);
			$result=json_decode($json[1]);
			if($result->status!=800){
				echo $result->error;
				exit();
			}
			return true;
			
		}
		
		private function _api_options($fields,$source)
		{
			// Set all options of CURL
			$_set_opt=array(
							CURLOPT_URL => $source,
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_POST => 1,
							CURLINFO_HEADER_OUT => true,
							CURLOPT_HEADER => 1,
							CURLOPT_POSTFIELDS => http_build_query($fields)
			);
			
			return $_set_opt;
		}
		
		function _post_requests($post)
		{
			// Sanitize post array
	
			$post = $this->_Sanitize->sanitize($post);
			
			// Set CURL Options
			
			$_Options = $this->_api_options($post,$this->_Source);
			
			// Return all options
			
			return $_Options;
		}
		
		function _get_stock_value($post)
		{
			$this->_api->init();
			$this->_api->setopt_array($this->_post_requests($post));
			$data=$this->_api->exec();
			//echo $this->_api->error();
			$this->_api->close();
			return $data;
		}
		
		function _register_ikeyPass($post)
		{
			$this->_api->init();
			$this->_api->setopt_array($this->_post_requests($post));
			$data=$this->_api->exec();
			$this->_api->close();
			$json=explode('text/html',$data);
			$result=$json[1];
			return $result;
			/*if($result->status==803)
			{
				$this->_Sanitize->redirect($result->required_url,$result->fields);
				return $result;
			}
			else
			{
				$fields=array();
				foreach($result->post as $key=>$values){
					$fields[$key]=$values;
				}
				echo $this->_ajax->init();
				echo $this->_ajax->open();
				$success="window.location='".$fields['success']."'";
				echo $this->_ajax->set_ajax($fields['success'],$fields,$success);
				echo $this->_ajax->close();
			}*/
		}
		
		function _Login_ikeyPass($post)
		{
			$this->_api->init();
			$this->_api->setopt_array($this->_post_requests($post));
			$data=$this->_api->exec();
			$this->_api->close();
			$json=explode('text/html',$data);
			$result=$json[1];
			return $result;
		}
	}	
	
	
	
	
	
	
	//print_r($_ikeyPass->$method($_POST));
	
	//$_ikeyPass->$post->method."(".$post.")";
	  
	//print_r(sanitize($_POST));
	//$_data=json_encode($_POST);
	
	//print_r(json_decode($_data));
	
	
 
?>