<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: $
 *
 * @category Piwik_Plugins
 * @package Piwik_DBObsecurity
 */

/**
 * Encrypt / decrypt data
 *
 * In the context of connecting to the database server, Piwik is the client.  So, the
 * encrypted password is encrypted using a private key + passphrase.  The certificate
 * should then be removed from the filesystem.  Piwik will decrypt the encrypted password
 * using the public key.
 *
 * @package Piwik_DBObsecurity
 */
class Piwik_DBObsecurity_Crypt_OpenSSL implements Piwik_DBObsecurity_Crypt_Interface
{
	/**
	 * "Encrypt" the provided value
	 *
	 * @param string $value
	 * @param array $params
	 * @return string
	 */
	static function encrypt($value, $params)
	{
		$certificate = file_get_contents($params['private_key_path']);
		$private_key = openssl_pkey_get_private($certificate, $params['passphrase']);

		$encrypted = '';
		$rc = openssl_private_encrypt($value, $encrypted, $private_key);
		return base64_encode($encrypted);
	}

	/**
	 * "Decrypt" the provided value
	 *
	 * @param string $value
	 * @param array $params
	 * @return string
	 */
	static function decrypt($value, $params)
	{
		$certificate = file_get_contents($params['public_key_path']);
		$public_key = openssl_pkey_get_public($certificate);

		$value = base64_decode($value);
		$decrypted = $value;
		$rc = openssl_public_decrypt($value, $decrypted, $public_key);
		return $decrypted;
	}
}
