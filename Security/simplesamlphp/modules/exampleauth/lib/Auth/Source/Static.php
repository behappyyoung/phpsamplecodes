<?php

/**
 * Example authentication source.
 *
 * This class is an example authentication source which will always return a user with
 * a static set of attributes.
 *
 * @author Olav Morken, UNINETT AS.
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_exampleauth_Auth_Source_Static extends SimpleSAML_Auth_Source {


	/**
	 * The attributes we return.
	 */
	private $attributes;


	/**
	 * Constructor for this authentication source.
	 *
	 * @param array $info  Information about this authentication source.
	 * @param array $config  Configuration.
	 */
	public function __construct($info, $config) {
		assert('is_array($info)');
		assert('is_array($config)');

		/* Call the parent constructor first, as required by the interface. */
		parent::__construct($info, $config);


		/* Parse attributes. */
		try {
			$this->attributes = SimpleSAML_Utilities::parseAttributes($config);
		} catch(Exception $e) {
			throw new Exception('Invalid attributes for authentication source ' .
				$this->authId . ': ' . $e->getMessage());
		}

	}


	/**
	 * Log in using static attributes.
	 *
	 * @param array &$state  Information about the current authentication.

	public function authenticate(&$state) {
		assert('is_array($state)');

		$state['Attributes'] = $this->attributes;
	}
		 */
	// update young
	public function authenticate(&$state) {
		assert('is_array($state)');
		
		if(($_REQUEST['userid']!='')&&($_REQUEST['firstname']!='')&&($_REQUEST['lastname']!='')&&($_REQUEST['email']!='')){
			 $myval = array(
						'uid' => $_REQUEST['userid'],
						'userid' => $_REQUEST['userid'],
						'shnid' => $_REQUEST['userid'],						
						'groupid' => 'selfhealth',
						'employerid' => $_REQUEST['employerid'],
						'employername' => $_REQUEST['employername'],						
						'firstname' =>$_REQUEST['firstname'],
						'lastname' =>$_REQUEST['lastname'],
						'useremail' => $_REQUEST['email'],
				);
		}else{
			echo 'WRONG INPUT';
			exit();
//for testing
			 $myval = array(
				            'uid' => '6047',
					    'userid' => 'testuserid',
				            'firstname' =>'firstnameyoung',
					    'lastname' =>'lastnamepark',
				            'useremail' =>'emailypark@selfhealthnet.com',
				);
		}
		$state['Attributes'] = SimpleSAML_Utilities::parseAttributes($myval);

	}	

}

?>