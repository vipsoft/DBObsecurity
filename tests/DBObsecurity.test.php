<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../../tests/config_test.php";
}

require_once dirname(__FILE__).'/../DBObsecurity.php';

class Test_Piwik_DBObsecurity extends UnitTestCase
{
	function test_unlockDatabaseConfig()
	{
		$public_key_path = dirname(__FILE__).'/public_key.pem';
                $this->assertTrue(file_exists($public_key_path));

		$config = array(
			'OpenSSL' => array(
				'public_key_path' => $public_key_path,
			),
		);

		$plaintext = 'Hello world';
                $encrypted = 'Y0nT7/fc37EUQEBE6gRlclIlfvo82JlWCxXskVSBc/EBFKtThJ2+Oh22vsDCP9UWkqT2vYK2SrGMWY9cM5h1FDfRfNo7fm26HcrZ7ra7UNzVpxC9jFoVvFr/mcxmocnY8vyISohEMmXr25Jo9n6s5iLCOw/fNLRdRCKzNf8Muvs=';

		$objUnderTest = new Piwik_DBObsecurity();

		// passthru
		$res = $objUnderTest->unlockDatabaseConfig($config, $plaintext);
		$this->assertTrue($res === $plaintext);

		$res = $objUnderTest->unlockDatabaseConfig($config, $encrypted);
		$this->assertTrue($res === $encrypted);

		// password from environment variable
		$envVarName = 'PIWIK_DB_PASSWORD_'.time();
		$this->assertFalse(getenv($envVarName));

		putenv($envVarName . '=' . $plaintext);
		$res = $objUnderTest->unlockDatabaseConfig($config, 'ENV[' . $envVarName . ']');
		$this->assertTrue($res === $plaintext);

		putenv($envVarName . '=' . $encrypted);
		$res = $objUnderTest->unlockDatabaseConfig($config, 'ENV[' . $envVarName . ']');
		$this->assertTrue($res === $encrypted);

		// encrypted password
		$res = $objUnderTest->unlockDatabaseConfig($config, 'ENC[' . $encrypted . ']');
		$this->assertTrue($res === $plaintext);

		// encrypted password from environment variable
		putenv($envVarName . '=' . $encrypted);
		$res = $objUnderTest->unlockDatabaseConfig($config, 'ENC[ENV[' . $envVarName . ']]');
		$this->assertTrue($res === $plaintext);
	}
}
