<?php
error_reporting(E_ERROR);
function request($url, $data = null, $headers = null, $put = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
    endif;
    if($put):
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $put);
    endif;
    curl_setopt($ch, CURLOPT_ENCODING, "");
    return curl_exec($ch);
}

function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}

function username($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function phonenumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$username = username();
$phonenumber = phonenumber();
$url = "https://api.internal.temp-mail.io/api/v3/email/new";
$headers = array();
$headers[] = "Content-Type: application/json;charset=UTF-8";
$hedaers[] = "Accept-Encoding: gzip, deflate";
$data = '{"min_name_length":10,"max_name_length":10}';
$getEmail = request($url, $data, $headers);
if(strpos($getEmail, '"email":"')!==false)
{
    $email = getstr($getEmail, '"email":"','"');
    echo "Registering $email : ";
}
else
{
    echo "Failed to get Email\n$getEmail\n";
}


$url = "https://awsapi.play3.gg/api/member/register";
$headers = array();
$headers[] = 'Sec-Ch-Ua: "Chromium";v="105", "Not)A;Brand";v="8"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.5195.102 Safari/537.36';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Access-Control-Allow-Origin: *';
$headers[] = 'Accept: application/json';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Sec-Ch-Ua-Platform: "Windows"';
$headers[] = 'Origin: https://app.play3.gg';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://app.play3.gg/';
$headers[] = 'Accept-Encoding: gzip, deflate';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$data = '{"email":"'.$email.'","password":"Asdasd123","password_confirmation":"Asdasd123","phone_number":"81'.$phonenumber.'"}';
$regist = request($url, $data, $headers);
if(strpos($regist, '"success":true')!==false)
{
    echo "Success!\n";
    echo "Confirming Email : ";
    mail:
    $url = "https://api.internal.temp-mail.io/api/v3/email/$email/messages";
    $headers = array();
    $headers[] = "Accept-Encoding: gzip, deflate";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $getLink = request($url, $data = null, $headers);
    if(strpos($getLink, 'PLAY3')!==false)
    {
        $token = getstr($getLink, 'token=','\n');
        $url = "https://awsapi.play3.gg/email-verif?token=$token";
        $confirm = request($url, $data = null, $headers);
        if(strpos($confirm, 'Email Verification Success!')!==false)
        {
            echo "Confirmed!\n";
        }
        else
        {
            echo "Failed to confirming email\n";
            exit();
        }
    }
    else
    {
        sleep(1);
        goto mail;
    }

}
else
{
    echo "Failed to Regist\n";
    echo "$regist\n";
    exit();
}

echo "Login : ";
$url = "https://awsapi.play3.gg/api/member/login";
$headers = array();
$headers[] = 'Sec-Ch-Ua: "Chromium";v="105", "Not)A;Brand";v="8"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.5195.102 Safari/537.36';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Access-Control-Allow-Origin: *';
$headers[] = 'Accept: application/json';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Sec-Ch-Ua-Platform: "Windows"';
$headers[] = 'Origin: https://app.play3.gg';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://app.play3.gg/';
$headers[] = 'Accept-Encoding: gzip, deflate';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$data = '{"email":"'.$email.'","password":"Asdasd123"}';
$login = request($url, $data, $headers);
if(strpos($login, '"Success Members Login Authenticate"')!==false)
{
    echo "Success\n";
    $accessToken = getstr($login, '"access_token":"','"');
}
else
{
    echo "Failed to login\n";
    exit();
}

echo "Set Username : ";
$url = "https://awsapi.play3.gg/api/member/claim-username";
$headers = array();
$headers[] = 'Sec-Ch-Ua: "Chromium";v="105", "Not)A;Brand";v="8"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Authorization: Bearer '.$accessToken.'';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.5195.102 Safari/537.36';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Access-Control-Allow-Origin: *';
$headers[] = 'Accept: application/json';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Sec-Ch-Ua-Platform: "Windows"';
$headers[] = 'Origin: https://app.play3.gg';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://app.play3.gg/';
$headers[] = 'Accept-Encoding: gzip, deflate';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
$data = '{"username":"'.$username.'"}';
$claimUsername = request($url, $data, $headers);
if(strpos($claimUsername, '"success":true')!==false)
{
    echo "Success\n";
}
else
{
    echo "Failed to set username\n";
    echo "$claimUsername\n";
    exit();
}

echo "Start Learn 'Apa itu NFT'\n";
a:
echo "Watch Video 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":130,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    echo "$watch\n";
    goto a;
}

b:
echo "Watch Video 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":131,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto b;
}

c:
echo "Answer Question 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":131,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":244,"quiz_answer_id":908}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto c;
}
else
{
    echo "Failed to watch\n$watch\n";
    goto c;
}

d:
echo "Watch Video 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":132,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto d;
}

e:
echo "Answer Question 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":132,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":245,"quiz_answer_id":909}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto e;
}
else
{
    echo "Failed to watch\n";
    goto e;
}

f:
echo "Watch Video 4 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":133,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto f;
}

g:
echo "Answer Question 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":133,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":246,"quiz_answer_id":913}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto g;
}
else
{
    echo "Failed to watch\n";
    goto g;
}

h:
echo "Watch Video 5 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":137,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto h;
}

i:
echo "Answer Question 4 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":137,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":254,"quiz_answer_id":946}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto i;
}
else
{
    echo "Failed to watch\n";
    goto i;
}


j:
echo "Watch Video 6 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":138,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto j;
}

k:
echo "Answer Question 5 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":138,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":255,"quiz_answer_id":950}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto k;
}
else
{
    echo "Failed to watch\n";
    goto k;
}

l:
echo "Watch Video 7 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":134,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto l;
}

m:
echo "Answer Question 6 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":134,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":252,"quiz_answer_id":939}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto m;
}
else
{
    echo "Failed to watch\n";
    goto m;
}


n:
echo "Watch Final Video : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":135,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto n;
}

o:
echo "Answer Final Question : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-final-quiz-answer";
$data = '{"learn_id":32,"is_timeout":0,"member_quiz_answers":[{"quiz_question_id":250,"quiz_answer_id":930},{"quiz_question_id":248,"quiz_answer_id":922},{"quiz_question_id":249,"quiz_answer_id":925},{"quiz_question_id":251,"quiz_answer_id":934},{"quiz_question_id":247,"quiz_answer_id":920}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto o;
}
else
{
    echo "Failed to watch\n";
    goto o;
}


$url = "https://awsapi.play3.gg/api/member/get-profile-popup-data";
$countPoints = request($url, $data = null, $headers);
$points = getstr($countPoints, '"total_point":','}');
echo "Total Points : $points\n";

echo "Start Learn 'DeFI'\n";
p:
echo "Watch Video 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":175,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto p;
}

q:
echo "Answer Question 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":175,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":337,"quiz_answer_id":1261}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto q;
}
else
{
    echo "Failed to watch\n";
    goto q;
}

r:
echo "Watch Video 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":176,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto r;
}

s:
echo "Answer Question 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":176,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":338,"quiz_answer_id":1264}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto s;
}
else
{
    echo "Failed to watch\n";
    goto s;
}

t:
echo "Watch Video 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":177,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto t;
}

u:
echo "Answer Question 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":177,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":339,"quiz_answer_id":1268}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto u;
}
else
{
    echo "Failed to watch\n";
    goto u;
}

zz:
echo "Watch Video 4 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":178,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto zz;
}

v:
echo "Answer Question 4 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":178,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":340,"quiz_answer_id":1272}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto v;
}
else
{
    echo "Failed to watch\n";
    goto v;
}

w:
echo "Answer Final Question : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-final-quiz-answer";
$data = '{"learn_id":42,"is_timeout":0,"member_quiz_answers":[{"quiz_question_id":335,"quiz_answer_id":1253},{"quiz_question_id":336,"quiz_answer_id":1260},{"quiz_question_id":334,"quiz_answer_id":1251},{"quiz_question_id":332,"quiz_answer_id":1243},{"quiz_question_id":333,"quiz_answer_id":1245}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto w;
}
else
{
    echo "Failed to watch\n";
    goto w;
}


$url = "https://awsapi.play3.gg/api/member/get-profile-popup-data";
$countPoints = request($url, $data = null, $headers);
$points = getstr($countPoints, '"total_point":','}');
echo "Total Points : $points\n";

echo "Start Learn 'Mengenal Cryptocurrency'\n";
x:
echo "Watch Video 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":11,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto x;
}


y:
echo "Watch Video 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":12,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto y;
}


z:
echo "Answer Question 1 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":12,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":48,"quiz_answer_id":181}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto z;
}
else
{
    echo "Failed to watch\n";
    goto z;
}


aa:
echo "Watch Video 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":13,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto aa;
}

bb:
echo "Answer Question 2 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":13,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":49,"quiz_answer_id":182}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto bb;
}
else
{
    echo "Failed to watch\n";
    goto bb;
}


cc:
echo "Watch Video 4 : ";
$url = "https://awsapi.play3.gg/api/dashboard/update-learn-video-progress";
$data = '{"learn_content_id":14,"last_watched_at":81,"is_video_finished":true}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else
{
    echo "Failed to watch\n";
    goto cc;
}


dd:
echo "Answer Question 3 : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-content-quiz-answer";
$data = '{"learn_content_id":14,"is_timeout":false,"member_quiz_answers":[{"quiz_question_id":50,"quiz_answer_id":187}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto dd;
}
else
{
    echo "Failed to watch\n";
    goto dd;
}


ee:
echo "Answer Final Question : ";
$url = "https://awsapi.play3.gg/api/dashboard/submit-learn-final-quiz-answer";
$data = '{"learn_id":4,"is_timeout":0,"member_quiz_answers":[{"quiz_question_id":73,"quiz_answer_id":264},{"quiz_question_id":75,"quiz_answer_id":273},{"quiz_question_id":72,"quiz_answer_id":258},{"quiz_question_id":76,"quiz_answer_id":275},{"quiz_question_id":74,"quiz_answer_id":269}]}';
$watch = request($url, $data, $headers);
if(strpos($watch, '"success":true')!==false)
{
    echo "Done!\n";
}
else if(strpos($watch, 'retry_after":')!==false)
{
    $retryAfter = getstr($watch, 'retry_after":','}');
    echo "Retry After $retryAfter\n";
    sleep($retryAfter);
    goto o;
}
else
{
    echo "Failed to watch\n";
    goto ee;
}

$url = "https://awsapi.play3.gg/api/dashboard/claim-exp";
$data = '{"quest_id":24}';
$claimLogin = request($url, $data, $headers);

echo "Email : $email\n";

echo "Enter when you already connect wallet";
trim(fgets(STDIN));

echo "Roll : ";
$url = "https://awsapi.play3.gg/api/offchain/roulette/roll";
$data = '{"game_name":"mobile-legends","num_of_spin":1}';
$roll = request($url, $data, $headers);
if(strpos($roll, '"name":"')!==false)
{
    $result = getstr($roll, '"name":"','"');
    echo "$result\n";
}
else
{
    echo "failed when roll\n";
}

