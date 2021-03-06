<?php

namespace Ghlin\DingtalkEapp;

use Ghlin\DingtalkEapp\Util\Http;
use Ghlin\DingtalkEapp\Crypto\DingtalkCrypt;

class DingtalkEapp {

    private $suiteKey;
    private $suiteSecret;
    private $token;
    private $encodingAesKey;
    private $appId;
    private $appSecret;

    public function __construct() {
        $this->suiteSecret = config('dingtalkeapp.suite_secret');
        $this->token = config('dingtalkeapp.token');
        $this->encodingAesKey = config('dingtalkeapp.encoding_aes_key');
        $this->suiteKey = config('dingtalkeapp.suite_key');
        $this->appId = config('dingtalkeapp.app_id');
        $this->appSecret = config('dingtalkeapp.app_secret');
    }

    public function getCorpToken($authCorpId) {
        $timeStamp = time() * 1000;
        $suiteTicket = $this->getSuiteTicket($this->suiteKey);
        $msg = $timeStamp."\n".$suiteTicket;
        $sha = urlencode(base64_encode(hash_hmac('sha256', $msg, $this->suiteSecret, true)));
        $res = Http::post("/service/get_corp_token",
            array(
                "accessKey" => $this->suiteKey,
                "timestamp" => $timeStamp,
                "suiteTicket" => $suiteTicket,
                "signature" => $sha,
            ),json_encode(array(
                "auth_corpid" => $authCorpId,
            )));
        return $res;
    }

    public function getAuthCorpInfo($authCorpId) {
        $timeStamp = time() * 1000;
        $suiteTicket = $this->getSuiteTicket($this->suiteKey);
        $msg = $timeStamp."\n".$suiteTicket;
        $sha = urlencode(base64_encode(hash_hmac('sha256', $msg, $this->suiteSecret, true)));
        $res = Http::post("/service/get_auth_info",
            array(
                "accessKey" => $this->suiteKey,
                "timestamp" => $timeStamp,
                "suiteTicket" => $suiteTicket,
                "signature" => $sha,
            ),json_encode(array(
                "auth_corpid" => $authCorpId,
            )));
        return $res->auth_corp_info;
    }

    public function getUserInfo($accessToken, $code) {

        $res = Http::get("/user/getuserinfo",
            array(
                "access_token" => $accessToken,
                "code" => $code,
            ));
        $detail = Http::get("/user/get",
            array(
                "access_token" => $accessToken,
                "userid" => $res->userid,
            ));
        return $detail;
    }

    public function getUserInfoByCode($authCode) {
        $timeStamp = time() * 1000;
        $msg = $timeStamp;
        $sha = urlencode(base64_encode(hash_hmac('sha256', $msg, $this->appSecret, true)));
        $res = Http::post("/sns/getuserinfo_bycode",
            array(
                "accessKey" => $this->appId,
                "timestamp" => $timeStamp,
                "signature" => $sha,
            ),json_encode(array(
                "tmp_auth_code" => $authCode,
            )));
        return $res;
    }

    public function getSuiteTicket($suiteKey) {
        return file_get_contents(realpath(base_path('resources')).'/views/vendor/dingtalk/suite_ticket.txt');
    }

    public function DecryptMsg($signature, $timeStamp, $nonce, $postdata) {
        $postList = json_decode($postdata,true);
        $encrypt = $postList['encrypt'];

        $crypt = new DingtalkCrypt($this->token, $this->encodingAesKey, $this->suiteKey);
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt);
        return $errCode;
    }

    public function EncryptMsg($res, $timeStamp, $nonce) {
        $crypt = new DingtalkCrypt($this->token, $this->encodingAesKey, $this->suiteKey);
        $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce);
        return $errCode;
    }

}
