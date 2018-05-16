<?php 

class CurlMgr {
    private $c;
    private $res;
    private $err;
    private $errCode;

    private $methods = [
        "GET",
        "HEAD",
        "POST",
        "PUT",
        "DELETE",
        "CONNECT",
        "OPTIONS",
        "TRACE",
        "PATCH"
    ];

    public $headers = [];

    function __construct($url=false, Array $data=[]){
        $this->c = curl_init('');
        curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->c, CURLINFO_HEADER_OUT, true);
        
        if ($url) {
            $this->setUrl($url, $data);
        }
    }

    public function setHeader($heads){
        if (is_string($heads) && strpos($heads, ': ')) {
            $h = explode(': ', $heads);

            $heads = [];
            $heads[$h[0]] = $h[1];
        }

        if (is_array($heads)) {
            if (!$this->headers) {
                $this->headers = [];
            }

            $this->headers = array_merge($this->headers, $heads);
        }
        elseif($heads){
            $this->headers[] = $heads;
        }
        else{
            $this->headers = false;
        }
        // curl_setopt($this->c, CURLOPT_HTTPHEADER, $heads);
        return $this;
    }

    public function setUrl($url, Array $data=[]){
        if (count($data)) {
            $d = http_build_query($data);
            
            if (!strpos($url, '?')) {
                $sep = '?';
            }
            elseif(strpos($url, '&') || substr($url, -1) != '?'){
                $sep = '&';
            }
            else{
                $sep = '';
            }

            $url .= $sep.$d;
        }

        curl_setopt($this->c, CURLOPT_URL, $url);
        return $this;
    }

    public function setData($data, $asJson=true, $addHeaders=true){
        if(is_array($data)){
            if ($asJson) {
                $data = json_encode($data);
                $this->setHeader("Content-Type: application/json");
            }
            else{
                $data = http_build_query($data);
                $this->setHeader("Content-Type: application/x-www-form-urlencoded");
            }
        }

        $this->setHeader("Content-Length: ".strlen($data));
        curl_setopt($this->c, CURLOPT_POSTFIELDS, $data);

        return $this;
    }

    public function reset($url=""){
        curl_reset($this->c);
        curl_close($this->c);

        $this->c = curl_init($url);
        curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->c, CURLINFO_HEADER_OUT, true);

        $this->err = "";
        $this->res = NULL;
        $this->errCode = 0;
        $this->headers = [];

        return $this;
    }

    public function setUser($user){
        curl_setopt($this->c, CURLOPT_USERPWD, $user);
        return $this;
    }

    private function applyHeaders(){
        $heads = [];
        foreach ($this->headers as $k => $v) {
            if (is_string($k)) {
                $heads[] = "{$k}: {$v}";
            }
            else{
                $heads[] = $v;
            }
        }
        $t = curl_setopt($this->c, CURLOPT_HTTPHEADER, $heads);
    }

    public function exec(){
        $this->applyHeaders();
        $this->res = curl_exec($this->c);
        $this->err = curl_error($this->c);
        $this->errCode = curl_errno($this->c);

        return $this->res;
    }

    public function getError(){
        return ["code" => $this->errCode, "err" => $this->err];
    }

    public function getInfos($opt=0){
        if ($opt) {
            return curl_getinfo($this->c, $opt);
        }

        return curl_getinfo($this->c);
    }

    public function isPost($p=true){
        curl_setopt($this->c, CURLOPT_POST, $p);
        return $this;
    }

    public function verifySSL($s=true){
        curl_setopt($this->c, CURLOPT_SSL_VERIFYHOST, $s);
        curl_setopt($this->c, CURLOPT_SSL_VERIFYPEER, $s);
        return $this;
    }

    public function verbose($v=true){
        curl_setopt($this->c, CURLOPT_VERBOSE, $v);
        return $this;
    }

    public function responseHeader($v=true){
        curl_setopt($this->c, CURLOPT_HEADER, $v);
        return $this;
    }

    public function setMethod($m){
        $m = strtoupper($m);
        if (!in_array($m, $this->methods)) {
            throw new Exception("Error: HTTP Method '{$m}' Does not exist", 1);
        }

        curl_setopt($this->c, CURLOPT_CUSTOMREQUEST, $m);
        return $this;
    }

    public function setOpt($opt, $data){
        curl_setopt($this->c, $opt, $data);
        return $this;
    }

    public function close(){
        curl_close($this->c);
    }

    public function setTimeout($sec) {
        curl_setopt($this->c, CURLOPT_TIMEOUT, $sec);
    }
}

?>