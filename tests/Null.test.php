<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../../tests/config_test.php";
}

class Test_Piwik_DBObsecurity_Crypt_Null extends UnitTestCase
{
	function test_encrypt()
	{
		$value = 'Mi$3cr3tP4$$w0rd';

		$crypt = new Piwik_DBObsecurity_Crypt_Null();
		$encrypted = $crypt->encrypt(
			$value,
			null
		);

		$this->assertTrue($encrypted === $value);
	}

	function test_decrypt()
	{
		$value = 'Y0nT7/fc37EUQEBE6gRlclIlfvo82JlWCxXskVSBc/EBFKtThJ2+Oh22vsDCP9UWkqT2vYK2SrGMWY9cM5h1FDfRfNo7fm26HcrZ7ra7UNzVpxC9jFoVvFr/mcxmocnY8vyISohEMmXr25Jo9n6s5iLCOw/fNLRdRCKzNf8Muvs=';

		$crypt = new Piwik_DBObsecurity_Crypt_Null();
		$decrypted = $crypt->decrypt(
			$value,
			null
		);

		$this->assertTrue($decrypted === $value);
	}
}
