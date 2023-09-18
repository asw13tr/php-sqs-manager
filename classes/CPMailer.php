<?php



require_once __DIR__ . "/../plugins/phpmailer/src/PHPMailer.php";
require_once __DIR__ . "/../plugins/phpmailer/src/SMTP.php";
require_once __DIR__ . "/../plugins/phpmailer/src/Exception.php";


class CPMailer{

    private $messages = [
        'error'         => 'Beim Senden der E-Mail ist ein Problem aufgetreten.',
        "success"       => "die Email wurde verschickt."
    ];

    private $debugMode = 0;

    private $host, $user, $pass, $secure, $port;

    private $from = ["", ""];

    private $to = [];

    private $replyTo = null;

    private $cc = [];

    private $bcc = [];

    private $attachments = [];

    private $title, $body;

    private $retryLimit = 3;

    public function __construct($datas=[]){
        $this->host = $datas["host"] ?? "smtp.strato.de";
        $this->user = $datas["user"] ?? "";
        $this->pass = $datas["pass"] ?? "";
        $this->port = $datas["port"] ?? 465;

        $secureMode = $datas["secure"] ?? "ssl";
        if($secureMode=="tls"){
            $this->secure =  PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }else{
            $this->secure =  PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        }

    }

    public function setDebug($modeInt=0){ //0,1,2,3,4
        $modes = [
            \PHPMailer\PHPMailer\SMTP::DEBUG_OFF,
            \PHPMailer\PHPMailer\SMTP::DEBUG_CLIENT,
            \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER,
            \PHPMailer\PHPMailer\SMTP::DEBUG_CONNECTION,
            \PHPMailer\PHPMailer\SMTP::DEBUG_LOWLEVEL
        ];
        $this->debugMode = $modes[$modeInt] ?? \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
        return $this;
    }

    public function from($mail, $name=""){
        $this->from = [$mail, $name];
        return $this;
    }

    public function to($mail, $name=""){
        $this->to[] = [$mail, $name];
        return $this;
    }


    public function addReplyTo($mail, $name=""){
        $this->replyTo = [$mail, $name];
        return $this;
    }

    public function addCC($mail){
        $this->cc[] = $mail;
        return $this;
    }

    public function addBCC($mail){
        $this->bcc[] = $mail;
        return $this;
    }

    public function attach($path, $name=""){
        $this->attachments[] = [$path, $name];
        return $this;
    }

    public function content($title="", $body='', $allowHtml=true){
//        $this->mail->isHTML($allowHtml);
        $this->title = $title;
        $this->body = $body;
        return $this;
    }

    public function setRetryLimit($limit=3){
        $this->retryLimit = $limit;
        return $this;
    }

    private function sendMail(){
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            //Server Einstellungen
            $mail->SMTPDebug = $this->debugMode;
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->user;
            $mail->Password   = $this->pass;
            $mail->SMTPSecure = $this->secure;
            $mail->Port       = $this->port;

            $mail->setFrom($this->from[0], $this->from[1]);

            $replyTo = $this->replyTo ?? $this->from;
            $mail->addReplyTo($replyTo[0], $replyTo[1]);

            foreach ($this->to as $receiver){
                $mail->addAddress($receiver[0], $receiver[1]);
            }

            foreach ($this->cc as $ccMail){
                $mail->addCC($ccMail);
            }

            foreach ($this->bcc as $bccMail){
                $mail->addCC($bccMail);
            }

            foreach ($this->attachments as $attach){
                $mail->addAttachment($attach[0], $attach[1]);
            }

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->title;
            $mail->Body    = $this->body;

            $mail->send();

            $result = (object)[
                "status"  => true,
                "message" => $this->messages["success"]
            ];
        } catch (Exception $e) {
            $result = (object)[
                "status"  => false,
                "message" => $this->messages["error"],
                "info"    => $mail->ErrorInfo
            ];
        }
        return $result;
    }



    public function send(){

        for($i=1; $i<=$this->retryLimit; $i++){
            $process = $this->sendMail();
            if($process->status){
                break;
            }
        }

        return $process;
    }


}


?>