<?php

class sspmod_mymodule_Auth_Source_MyAuth extends sspmod_core_Auth_UserPassBase {
	private $username;
	private $password;
	
	public function __construct($info, $config) {
		parent::__construct($info, $config);
		if (!is_string($config['username'])) {
			throw new Exception('Missing or invalid username option in config.');
		}
		$this->username = $config['username'];
		if (!is_string($config['password'])) {
			throw new Exception('Missing or invalid password option in config.');
		}
		$this->password = $config['password'];
	}
	
    protected function login($username, $password) {
        if ($username !== 'theusername' || $password !== 'thepassword') {
            throw new SimpleSAML_Error_Error('WRONGUSERPASS');
        }
        return array(
            'uid' => array('theusername'),
            'displayName' => array('Some Random User'),
            'eduPersonAffiliation' => array('member', 'employee'),
        );
    }
}

?>