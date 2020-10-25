<?php


namespace Nekrida\Core;


use PDO;

class Database {
	/** @var PDO[] */
	protected static $instances = [];

	public static function setInstance($databaseCfg) {
		if (!isset($databaseCfg['name']))
			$databaseCfg['name'] = $databaseCfg['schema'];
		if (isset($databaseCfg['host']) && !empty($databaseCfg['host'])) {
			self::setInstanceByHostPort($databaseCfg['name'],$databaseCfg['driver'],$databaseCfg['host'],$databaseCfg['port'],
				$databaseCfg['schema'],$databaseCfg['login'],$databaseCfg['password']);
		} else {
			self::setInstanceBySocket($databaseCfg['name'],$databaseCfg['driver'],$databaseCfg['socket'],$databaseCfg['schema'],$databaseCfg['login'],$databaseCfg['password']);
		}
	}

	public static function setInstanceByHostPort($name,$driver,$host,$port,$schema,$login,$password) {
		if (!isset(self::$instances[$name])) {
			self::$instances[$name] = new PDO($driver .
				':host=' . $host .
				((!empty($post)) ? (';port=' . $port) : '') .
				';dbname=' . $schema, $login, $password);
			self::$instances[$name]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if (count(self::$instances) == 1)
				self::$instances[0] = self::$instances[$name];
		}
	}

	public static function setInstanceBySocket($name,$driver,$socket,$schema,$login,$password) {
		if (!isset(self::$instances[$name])) {
			self::$instances[$name] = new PDO($driver . ":unix_socket=" . $socket . ";dbname=" . $schema, $login, $password);
			self::$instances[$name]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if (count(self::$instances) == 1)
				self::$instances[0] = self::$instances[$name];
		}
	}

    /**
     * Returns database instance by name
     * @param int|string $schema $schema = 0 is the 'main' schema
     * @return PDO
     */
	public static function getInstance($schema = 0) {
		return self::$instances[$schema];
	}
}