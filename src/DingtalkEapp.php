<?php 

namespace Ghlin\DingtalkEapp;

use Ghlin\DingtalkEapp\Util\Http;

class DingtalkEapp {
    
    public static function getCorpToken($authCorpId) {
        $suiteKey = config('dingtalkeapp.suite_key');
        $timeStamp = time() * 1000;
        $suiteTicket = getSuiteTicket($suiteKey);
        $msg = $timeStamp."\n".$suiteTicket;
        $sha = urlencode(base64_encode(hash_hmac('sha256', $msg, $suiteKey, true)));
        $res = Http::post("/service/get_corp_token",
            array(
                "accessKey" => $suiteKey,
                "timestamp" => $timeStamp,
                "suiteTicket" => $suiteTicket,
                "signature" => $sha,
            ),json_encode(array(
                "auth_corpid" => $authCorpId,
            )));
        return $res;
    }
    
    public static function getUserInfo($accessToken, $code) {
        $res = Http::get("/user/getuserinfo",
            array(
                "access_token" => $accessToken,
                "code" => $code,
            ));
        return $res;
    }
    
    public static function getSuiteTicket($suiteKey) {
        return 'temp_suite_ticket_only4_test';
    }
}