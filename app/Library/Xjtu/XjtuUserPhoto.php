<?php
namespace App\Library\Xjtu;

use SoapClient;

class XjtuUserPhoto
{
    protected $url;
    protected $auth;

    /**
     * XjtuUserPhoto constructor.
     *
     * @param string $url
     * @param string $auth
     */
    public function __construct($url, $auth)
    {
        $this->url = $url;
        $this->auth = $auth;
    }

    /**
     * @param string $func_name
     * @param array $params
     *
     * @return mixed
     */
    protected function soapCall($func_name, $params = array())
    {
        $client = new SoapClient($this->url);
        $params['auth'] = $this->auth;
        $result = $client->__soapCall($func_name, array($params));
        if (!$result || !property_exists($result, 'return')) {
            return null;
        }
        return $result->return;
    }

    /**
     * @param string $stu_id
     *
     * @return string
     */
    public function getPhotoByStuId($stu_id)
    {
        return $this->soapCall('getPhotoByNo', array(
            'sno' => $stu_id,
        ));
    }
}
