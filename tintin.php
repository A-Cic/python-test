<?php
//認証情報設定
$authentication_array["channel_id"] = "1563458677";
$authentication_array["channel_secret"] = "613d2a45e10f435832d56bca81ead92d";


//データ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データからの抽出
$message_from = $json_object->{"result"}[0]->{"content"}->{"from"};    //メッセージ送信者
$message_text = $json_object->{"result"}[0]->{"content"}->{"text"};    //送信されたメッセージ

//返信メッセージ
$return_message_text = "「" . $message_text . "」じゃねーよｗｗｗ";

//返信メッセージの送信
sending_messages($authentication_array, $message_from, $return_message_text);

?>
<?php
//メッセージの送信
function sending_messages($authentication_array, $message_from, $return_message_text){
    //API URL
    $url = "https://trialbot-api.line.me/v1/events";

    //返信メッセージの設定
    $message_format = ['contentType' => 1,"toType" => 1,"text" => $return_message_text];

    //POSTデータ
    $post_data = [
        "to" => [$message_from],
        "toChannel" => "1383378250",
        "eventType" => "138311608800106203",
        "content" => $message_format
    ];

    //ヘッダー設定
    $headers = array(
        "Content-Type: application/json",
        "X-Line-ChannelID: " . $authentication_array["channel_id"],
        "X-Line-ChannelSecret: " . $authentication_array["channel_secret"],
    );

    //curl実行
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'X-Line-ChannelID: ' . $authentication_array["channel_id"],
        'X-Line-ChannelSecret: ' . $authentication_array["channel_secret"],
        'X-Line-Trusted-User-With-ACL: ' . $authentication_array["mid"]
    ));
    $result = curl_exec($ch);
    curl_close($ch);
