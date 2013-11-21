<?php
/**
 * SAML 2.0 remote SP metadata for simpleSAMLphp.
 *
 * See: http://simplesamlphp.org/docs/trunk/simplesamlphp-reference-sp-remote
 */



$metadata['https://www.mybrainsolutions.com/SSO/SelfHealth.aspx'] = array(
		'name' => array(
				'en' => 'mybrainsolutions - ',
				'no' => 'mybrainsolutions - ',
		),
                //	'AttributeNameFormat' =>':oasis:names:tc:SAML:2.0:attrname-format:uri',
               
    'AssertionConsumerService' => 'https://www.mybrainsolutions.com/SSO/SelfHealth.aspx',
    'SingleLogoutService'      => 'https://www.mybrainsolutions.com/SSO/SelfHealth.aspx',
 //   'base64attributes'         => TRUE,
);


$metadata['https://sandbox.mybrainsolutions.com/SSO/SelfHealth.aspx'] = array(
		'name' => array(
				'en' => 'mybrainsolutions - ',
				'no' => 'mybrainsolutions - ',
		),
                //	'AttributeNameFormat' =>':oasis:names:tc:SAML:2.0:attrname-format:uri',
               
    'AssertionConsumerService' => 'https://sandbox.mybrainsolutions.com/SSO/SelfHealth.aspx',
    'SingleLogoutService'      => 'https://sandbox.mybrainsolutions.com/SSO/SelfHealth.aspx',

    
);


$metadata['bhappier.net'] = array(
	'AssertionConsumerService' => 'http://testing.bhappier.net/simplesamlphp/www/module.php/saml/sp/saml2-acs.php/default-sp',
	'SingleLogoutService' => 'http://testing.bhappier.net/simplesamlphp/www//module.php/saml/sp/saml2-logout.php/default-sp',
);


$metadata['http://test.selfhealthnet.com/www/module.php/saml/sp/metadata.php/default-sp'] = array (
  'AssertionConsumerService' => 'http://test.selfhealthnet.com/www/module.php/saml/sp/saml2-acs.php/default-sp',
  'SingleLogoutService' => 'http://test.selfhealthnet.com/www/module.php/saml/sp/saml2-logout.php/default-sp',
);

$metadata['test.selfhealthnet.com'] = array (
  'AssertionConsumerService' => 'http://test.selfhealthnet.com/www/module.php/saml/sp/saml2-acs.php/default-sp',
  'SingleLogoutService' => 'http://test.selfhealthnet.com/www/module.php/saml/sp/saml2-logout.php/default-sp',
);
