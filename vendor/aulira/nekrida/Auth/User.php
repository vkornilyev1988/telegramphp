<?php
namespace Nekrida\Auth;

use Nekrida\Core\Database;
use Nekrida\Database\Query;

/**
 * 
 */
class User extends Query
{
	const TABLE_NAME = 'users';

	public static function guid() {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

	public static function getIdAndGroupByLogin($login,$password) {
		$st = Database::getInstance()->prepare("SELECT * FROM users WHERE login = ? AND active = 't'");
		$st->execute([$login]);
		$res = $st->fetch(2);

		if (empty($res))
			//WRONG LOGIN
			return false;
		$oldPassword = $res["password"];
		if (password_verify($password,$oldPassword)) {
		    unset($res['password']);
            return $res;
        } else
			return false;
	}

	public static function getInfoByLogin($login,$password) {
        $st = Database::getInstance()->prepare('SELECT * FROM users WHERE login = ?');
        $st->execute([$login]);
        $res = $st->fetch(2);
        if (empty($res))
            return false;
        $oldPassword = $res['password'];
        if (password_verify($password,$oldPassword)) {
            $res['guid'] = self::guid();
            $st = Database::getInstance()->prepare('UPDATE users SET guid = ? WHERE id = ?');
            $st->execute([$res['guid'],$res['id']]);
            unset($res['password']);
            return $res;
        }else
            return false;
    }
}