<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../../tests/config_test.php";
}

class Test_Piwik_DBObsecurity_Crypt_OpenSSL extends UnitTestCase
{
	function test_encrypt()
	{
		$passphrase = 'hello';
		$value = 'Mi$3cr3tP4$$w0rd';

		$private_key_path = dirname(__FILE__).'/private_key.pem';
		$this->assertTrue(file_exists($private_key_path));

		$crypt = new Piwik_DBObsecurity_Crypt_OpenSSL();
		$encrypted = $crypt->encrypt(
			$value,
			array(
				'private_key_path' => $private_key_path,
				'passphrase' => $passphrase,
			));

		$this->assertTrue($encrypted === 'sU++Y/eftwjvlkEbRzqkiBqlGgpp0RfyVn0s4b2CtAgXS7c78dY9a9iZDVlKmCWDhn1Qe09yKDXfZQkl0ZRKArTHZna78AVqsXUk90pt7SFSC3CXk2oNtGdVGi6P3o5pFE54hXDt+6f6LaG9z4TWOwNuEfb8xYKoS5FWsfK9yF8=');
	}

	function test_decrypt()
	{
		$value = 'Y0nT7/fc37EUQEBE6gRlclIlfvo82JlWCxXskVSBc/EBFKtThJ2+Oh22vsDCP9UWkqT2vYK2SrGMWY9cM5h1FDfRfNo7fm26HcrZ7ra7UNzVpxC9jFoVvFr/mcxmocnY8vyISohEMmXr25Jo9n6s5iLCOw/fNLRdRCKzNf8Muvs=';

		$public_key_path = dirname(__FILE__).'/public_key.pem';
		$this->assertTrue(file_exists($public_key_path));

		$crypt = new Piwik_DBObsecurity_Crypt_OpenSSL();
		$decrypted = $crypt->decrypt(
			$value,
			array(
				'public_key_path' => $public_key_path,
			));

		$this->assertTrue($decrypted === 'Hello world');
	}
}
