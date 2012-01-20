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
 * DBObsecurity - "Secure" your database connection credentials
 *
 * @package Piwik_DBObsecurity
 */
class Piwik_DBObsecurity extends Piwik_Plugin
{
	/**
	 * Get plugin information
	 *
	 * @return array
	 */
	public function getInformation()
	{
		return array(
			'description' => Piwik_Translate('DBObsecurity_PluginDescription'),
			'author' => 'Anthon Pang',
			'author_homepage' => 'http://securesystems.ca/',
			'version' => '1.0',
		);
	}

	/**
	 * Get list of hooks to register
	 *
	 * @return array
	 */
	public function getListHooksRegistered()
	{
		return array(
			'Reporting.getDatabaseConfig' => 'getDatabaseConfig',
			'Tracker.getDatabaseConfig' => 'getDatabaseConfig',
		);
	}

	/**
	 * Hook on Reporting.getDatabaseConfig to fetch/alter the database connection configuration.
	 *
	 * @param Piwik_Event_Notification $notification
	 */
	function getDatabaseConfig($notification)
	{
		$dbInfo =& $notification->getNotificationObject();

		$obj = new Piwik_Plugin_Config('DBObsecurity');
		$config = $obj->load();

		foreach ($dbInfo as $key => $value)
		{
			$dbInfo[$key] = $this->unlockDatabaseConfig($config, $value);
		}
	}

	/**
	 * "Unlock" the database config value
	 *
	 * @param array $config
	 * @param string $value
	 * @return string
	 */
	function unlockDatabaseConfig($config, $value)
	{
		// retrieve value from environment variable
		if (preg_match('~^ENV\[(.*)\]$~', $value, $matches))
		{
			$res = getenv($this->unlockDatabaseConfig($config, $matches[1]));
			return $res !== false ? $res : '';
		}

		// decrypt value
		if (preg_match('~^ENC\[(.*)\]$~', $value, $matches))
		{
			$crypt = Piwik_DBObsecurity_Crypt::factory('OpenSSL');
			return $crypt->decrypt($this->unlockDatabaseConfig($config, $matches[1]), $config['OpenSSL']);
		}

		return $value;
	}
}
