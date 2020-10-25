<?php


namespace App\Modules;


use Nekrida\Database\Query;

class Certificate extends Query
{
    public const TABLE_NAME = 'certificates';

    //For openssl_csr_sign
    public static $caPath = '{root}/ca/ca.crt';
    public static $caKey = '{root}/ca/ca.key';

    public static $userCertificatePath = '{root}/user/{user}.crt';
    public static $userCertificateKeyPath = '{root}/user/{user}.key';

    public static function generate($options,$ca = null, $caKey = null) {
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        ]);
        $csr = openssl_csr_new([
            "countryName" => $options['countryName'],
            "organizationName" => $options['organizationName'], //БИН
            "commonName" => $options['commonName'],
            "emailAddress" => $options['emailAddress']
        ], $privateKey, [
            'digest_alg' => 'sha256',
            'req_extensions' => 'v3_req'
        ]);
        openssl_pkey_export($privateKey,$keyOut);
        $x509 = openssl_csr_sign($csr, $ca ?: null, $caKey ?: $privateKey, 365, [
            'digest_alg' => 'sha256',
            'x509_extensions' => $ca ? 'usr_cert' : 'v3_ca'
        ],$options['serial']);
        openssl_x509_export($x509, $crtOut);
        return [
            $crtOut,
            $keyOut
        ];
    }

    public static function generateCA($options,$rootPath,$days = 2048) {
        $caPath = str_replace('{root}',$rootPath,static::$caPath);
        $caKey = str_replace('{root}',$rootPath,static::$caKey);

        list($cert,$privateKey) = static::generate($options);

        Certificate::insertSet([
            'name' => $options['commonName'],
            'start_date' => date('Y-m-d h:i:s'),
            'end_date' => Date('Y-m-d h:i:s', strtotime("+{$days} days")),
            'status' => 't',
        ])->query();

        file_put_contents($caKey,$privateKey);
        file_put_contents($caPath,$cert);
    }

    public static function generateByUser($user, $rootPath, $days = 365) {
        $userData = Query::select(['u.surname','u.name','company' => 'c.name','login','iin'])
            ->table('users')
            ->join('companies')->onA('c.id','=','u.company')
            ->where('u.id','=',(int)$user)
            ->query()->fetch(2);

        /*$latestCA = self::select(['id'])
            ->where('status','=','t')
            ->orderBy('start_date')
            ->query()->fetch()[0];*/

        $options = [
            'countryName' => 'KZ',
            'organizationName' => $userData['company'],
            'commonName' => $userData['surname'] . ' ' . $userData['name'],
            'emailAddress' => $userData['login'],
            'serial' => $userData['iin'],
        ];

        // FOR openssl sign
        $caPath = str_replace('{root}',$rootPath,'file://'.static::$caPath);
        $caKey = str_replace('{root}',$rootPath,'file://'.static::$caKey);

        $userCertificatePath = str_replace(['{root}','{user}'],[$rootPath,$user],static::$userCertificatePath);
        $userCertificateKeyPath = str_replace(['{root}','{user}'],[$rootPath,$user],static::$userCertificateKeyPath);


        list($cert,$privateKey) = self::generate($options,$caPath,$caKey);

        Certificate::insertSet([
            '"user"' => (int)$user,
            'start_date' => date('Y-m-d h:i:s'),
            'end_date' => Date('Y-m-d h:i:s', strtotime("+{$days} days")),
            'status' => 't',
        ])->query();

        file_put_contents($userCertificateKeyPath,$privateKey);
        file_put_contents($userCertificatePath,$cert);

        return true;
    }

    public static function recallByUser($user) {
        Certificate::updateSet([
            'status' => 'f'
        ])->where('"user"','=',(int)$user)
            ->query();
        return true;
    }

}
