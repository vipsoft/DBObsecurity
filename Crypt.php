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
 * Factory
 *
 * @package Piwik_DBObsecurity
 */
class Piwik_DBObsecurity_Crypt
{
	/**
	 * Factory method for different cryptography algorithms
	 *
	 * @param string $name
	 * @return Piwik_DBObsecurity_Interface
	 */
	static public function factory($name)
	{
		if ($name === 'OpenSSL')
		{
			return new Piwik_DBObsecurity_Crypt_OpenSSL();
		}

		return new Piwik_DBObsecurity_Crypt_Null();
	}
}
