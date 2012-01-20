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
 * Interface
 *
 * @package Piwik_DBObsecurity
 */
interface Piwik_DBObsecurity_Crypt_Interface
{
	/**
	 * "Encrypt" the provided value
	 *
	 * @param string $value
	 * @param array $params
	 */
	static function encrypt($value, $params);

	/**
	 * "Decrypt" the provided value
	 *
	 * @param string $value
	 * @param array $params
	 * @return string
	 */
	static function decrypt($value, $params);
}
