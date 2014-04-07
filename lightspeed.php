<?php

// To enable debugging, uncomment the following
//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 1);
//require_once 'app/Mage.php';
//Mage::setIsDeveloperMode(true);
//ini_set('display_errors', 1);

if (!PageCache::doYourThing()) {
    include_once('index.php');
}

class PageCache {

    static private $isCookieNew = true;
    static private $sessionType = '';
    static private $rawSession = '';
    static private $session = '';
    static private $sessionConfig = array();
    static private $cacheEngine = '';
    static private $cacheData = array();
    static private $database = array();
    static private $conditions = array(); // loggedin, cart
    static private $initConditions = false;
    static private $holeContent = array();
    static private $request_path = '';
    static private $debugMode = false;
    static private $multiCurrency = false;
    static private $storeCode = false;
    static private $defaultCurrencyCode = '';
    static public $_messageExists = false;

    public static function doYourThing() {
        try {
            self::prepareDebugger();
            self::verifyConfigurationExists();
            self::loadConfiguration();
            self::redirectAdmin();
            self::initCookie();
            self::renderCachedPage();
            return true;
        } catch (Exception $e) {
            self::report("Error: {$e->getMessage()}", true);
            return false;
        }
    }

    public static function redirectAdmin() {
        // detect existance of 'admin' keyword and redirect immediately to index.php
        // todo, toss in some logic to allow custom admin url routes
        if (preg_match('/\/admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("admin interface detected");
        }
        if (preg_match('/\/enhancedgrid(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("admin interface detected");
        }
        if (preg_match('/\/restock(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("admin interface detected");
        }
        if (preg_match('/\/checkout(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("checkout detected");
        }
        if (preg_match('/\/customer(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("customer detected");
        }
        if (preg_match('/\/epay(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("epay detected");
        }
        if (preg_match('/\/votes(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("votes detected");
        }
        if (preg_match('/\/testimonials(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("testimonials detected");
        }
        if (preg_match('/\/udropshipadmin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("udropshipadmin detected");
        }
        if (preg_match('/\/udbatchadmin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("udbatchadmin detected");
        }
        if (preg_match('/\/udmultiadmin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("udmultiadmin detected");
        }
        if (preg_match('/\/udpoadmin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("udpoadmin detected");
        }
        if (preg_match('/\/ustockpoadmin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("ustockpoadmin detected");
        }
        if (preg_match('/\/wsalogger(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("wsalogger detected");
        }
        if (preg_match('/\/advancednewsletter_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("advancednewsletter_admin detected");
        }
        if (preg_match('/\/advancedreports_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("advancedreports_admin detected");
        }
        if (preg_match('/\/advancedreviews_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("advancedreviews_admin detected");
        }
        if (preg_match('/\/awadvancedsearch_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("awadvancedsearch_admin detected");
        }
        if (preg_match('/\/avail_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("avail_admin detected");
        }
        if (preg_match('/\/awcore_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("awcore_admin detected");
        }
        if (preg_match('/\/followupemail_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("followupemail_admin detected");
        }
        if (preg_match('/\/kbase_admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("kbase_admin detected");
        }
        if (preg_match('/\/admin_ordertags(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("admin_ordertags detected");
        }
        if (preg_match('/\/gridactions(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("gridactions detected");
        }
        if (preg_match('/\/evlikeanalytics(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("evlikeanalytics detected");
        }
        if (preg_match('/\/gomage_navigation(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("gomage_navigation detected");
        }
        if (preg_match('/\/export(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("export detected");
        }
        if (preg_match('/\/credit(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("credit detected");
        }
        if (preg_match('/\/customerimages(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("customerimages detected");
        }
        if (preg_match('/\/restock(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("restock detected");
        }
        if (preg_match('/\/economic(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("economic detected");
        }
        if (preg_match('/\/gls(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("gls detected");
        }
        if (preg_match('/\/webpack2(\/|$)/', $_SERVER['REQUEST_URI'])) {
            throw new Exception("webpack2 detected");
        }
    }

    public static function initCookie() {
        if (!isset($_COOKIE['frontend'])) {
            self::report("first time visitor, I will be creating a cookie from here");
            // create the cookie so Magento doesn't fail
            self::buildCookie();
        } else {
            self::report("not a new visitor, using old cookie");
            self::$isCookieNew = false;
        }
    }

    public static function buildCookie() {
        //todo - change the memcached
        //require_once 'app/Mage.php';
        //$request = new Zend_Controller_Request_Http();
        switch (self::$sessionType) {
            case 'memcached':
                self::report("memcached cookie init");
                break;
            case 'db':
                self::report("db cookie init");
                break;
            default:
                self::report("files cookie init");
                self::report("lifetime: " . self::getCookieLifetime());
                self::report("path: " . self::getDefaultCookiePath());
                self::report("domain: " . self::getDefaultCookieDomain());
                self::report(self::$sessionConfig['path']);
            /*
              try {
              session_set_cookie_params(
              self::getCookieLifetime()
              , self::getDefaultCookiePath()
              , self::getDefaultCookieDomain()
              , false
              , true
              );
              session_name('frontend');
              session_save_path(self::$sessionConfig['path']);
              session_start();
              } catch (Exception $e) {
              self::report("Error: {$e->getMessage()}", true);
              return false;
              }
             */
        }
    }

    public static function messageExists() {
        self::$_messageExists = false;
        if (!self::$isCookieNew) {
            self::$rawSession = self::getRawSession();
            if (preg_match('/_messages.*?{[^}]*?Mage_Core_Model_Message_(Success|Error|Notice).*?}/s', self::$rawSession) > 0) {
                $message = true;
                self::$_messageExists = true;
            }
        }
        return self::$_messageExists;
    }

    public static function initConditions() {
        if (self::$initConditions) {
            return;
        }
        // get the session_id from the cookie : $_COOKIE['frontend']
        if (!self::$isCookieNew) {
            $session = self::getSession();
            // see if they are a logged in customer
            if (isset($session['customer_base'])) {
                if (isset($session['customer_base']['id'])) {
                    // ensure they haven't logged out
                    if ((int) $session['customer_base']['id'] >= 1) {
                        self::$conditions[] = 'loggedin';
                    }
                }
            }
            // see if they have started a cart
            if (isset($session['checkout'])) {
                if (isset($session['checkout']['quote_id_1']) && ($quoteId = $session['checkout']['quote_id_1'])) {
                    $sql = "SELECT COUNT(*) FROM sales_flat_quote_item WHERE quote_id = $quoteId";
                    $rresult = mysqli_query(self::$database, $sql);
                    while ($rrow = mysqli_fetch_array($rresult)) {
                        if ((int) $rrow[0] >= 1) {
                            self::$conditions[] = 'cart';
                        }
                        break;
                    }
                }
            }
            //See if they have added items to a compare
            if (isset($session['catalog'])) {
                if (isset($session['catalog']['catalog_compare_items_count'])) {
                    if ($session['catalog']['catalog_compare_items_count'] > 0) {
                        self::$conditions[] = 'compare';
                    }
                }
            }
        }
        self::$initConditions = true;
    }

    public static function prepareSession() {
        if (!self::$session) {
            self::$session = @self::unserializeSession(self::getRawSession());
            if (!self::$session) {
                self::report("unable to parse the session, generally this is because the session has expired");
            }
        }
    }

    public static function get($key) {
        self::report("getting data from " . self::$cacheEngine);
        switch (self::$cacheEngine) {
            case 'memcached':
                return self::$cacheData['server']->get($key);
                break;
            case 'files':
                if ($data = @file_get_contents(self::$cacheData['path'] . "/" . md5($key))) {
                    return unserialize($data);
                }
                break;
        }
        return false;
    }

    public static function delete($key) {
        switch (self::$cacheEngine) {
            case 'memcached':
                return self::$cacheData['server']->delete($key);
                break;
            case 'files':
                unlink(self::$cacheData['path'] . "/" . md5($key));
                break;
        }
        return false;
    }

    public static function getCachedPage() {
        $key = $_SERVER['SERVER_NAME'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $key = 'SECURE_' . $key;
        }
        $key = preg_replace('/(\?|&|&&)debug_front=1/s', '', $key);

        if (self::$multiCurrency) {
            self::report("configuration set to use multi_currency");
            $key .= '_' . self::getCurrencyCode();
        }
        //$key .= '_' . Mage::app()->getStore()->getId();

        self::report("attempting to fetch url: $key");
        if ($data = self::get($key)) {
            if (self::messageExists()) {
                self::report("a global message exists, we must not allow a cached page");
                return false;
            }
            if (!empty($data[3])) {
                header("HTTP/1.0 404 Not Found");
                header("Status: 404 Not Found");
            }
            if (isset($data[1]) && $data[1]) {
                $disqualified = false;
                if ($data[1] == '*') { // auto disqualify when messages exist in the session
                    self::report("disqualified because the disqualifier is *");
                    $disqualified = true;
                } else {
                    self::initConditions();
                    $disqualifiers = explode(",", $data[1]);
                    if ($count = count($disqualifiers)) {
                        for ($i = 0; $i < $count; $i++) {
                            if (in_array($disqualifiers[$i], self::$conditions)) {
                                self::report("disqualified with {$disqualifiers[$i]}");
                                $disqualified = true;
                                break 1;
                            }
                        }
                    }
                }
                if ($disqualified) {
                    // handle dynamic content retrieval here
                    if (isset($data[2]) && $data[2]) {
                        self::report("attempting to retrieve hole punched content from {$data[2]}");
                        $_SERVER['REQUEST_URI'] = self::$request_path . "/" . $data[2];
                        require_once 'app/Mage.php';
                        ob_start();
                        umask(0);

                        /* Store or website code */
                        $mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';

                        /* Run store or run website */
                        $mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

                        Mage::run($mageRunCode, $mageRunType);
                        $content = ob_get_clean();
                        self::$holeContent = Zend_Json::decode($content);
                        return self::fillNoCacheHoles($data[0]);
                    } else {
                        self::report("valid disqualifiers without hole punch content... bummer", true);
                        return false;
                    }
                } else {
                    return $data[0];
                }
            } else {
                return $data[0];
            }
        } else {
            self::report("No match found in the cache store", true);
            return false;
        }
    }

    public static function getDefaultCookieDomain() {
        $domain = ".temashop.dk";
        try {
            $sql = "SELECT value FROM core_config_data WHERE path = 'web/cookie/cookie_domain' AND scope = 'default' AND scope_id = 0";
            $result = mysqli_query(self::$database, $sql);
            while ($row = mysqli_fetch_array($result)) {
                if (isset($row[0])) {
                    $domain = $row[0];
                }
            }
        } catch (Exception $e) {
            
        }

        return $domain;
    }

    public static function getDefaultCookiePath() {
        $path = "/";
        try {
            $sql = "SELECT value FROM core_config_data WHERE path = 'web/cookie/cookie_path' AND scope = 'default' AND scope_id = 0";
            $result = mysqli_query(self::$database, $sql);
            while ($row = mysqli_fetch_array($result)) {
                if (isset($row[0])) {
                    $path = $row[0];
                }
            }
        } catch (Exception $e) {
            
        }

        return $path;
    }

    public static function getCurrencyCode() {
        $currencyCode = '';
        $session = self::getSession();
        if ($session && isset($session[self::getStoreCode()])) {
            self::report("found the session data for store code: " . self::getStoreCode());
            if (isset($session[self::getStoreCode()]['currency_code'])) {
                self::report("found a currency code in the session: " + $session[self::getStoreCode()]['currency_code']);
                $currencyCode = $session[self::getStoreCode()]['currency_code'];
            }
        }
        if (!$currencyCode) {
            self::report("defaulting to default currency code: " . self::getDefaultCurrencyCode());
            $currencyCode = self::getDefaultCurrencyCode();
        }
        return $currencyCode;
    }

    public static function getSession() {
        if (!self::$session) {
            self::prepareSession();
        }
        return self::$session;
    }

    public static function getRawSession() {
        if (!self::$rawSession) {
            switch (self::$sessionType) {
                case 'db':
                    $sql = "SELECT session_data FROM core_session WHERE session_id = '{$_COOKIE['frontend']}'";
                    $result = mysqli_query(self::$sessionConfig['connection'], $sql);
                    if (count($result)) {
                        while ($row = mysqli_fetch_array($result)) {
                            return $row[0];
                        }
                    }
                    break;
                case 'memcached':
                    return self::$sessionConfig['server']->get($_COOKIE['frontend']);
                    break;
                case 'files':
                default:
                    return @file_get_contents(self::$sessionConfig['path'] . "/" . "sess_" . $_COOKIE['frontend']);
                    break;
            }
        }
        return self::$rawSession;
    }

    public static function unserializeSession($data) {
        $result = false;
        if ($data) {
            $vars = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/', $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $numElements = count($vars);
            for ($i = 0; $numElements > $i && $vars[$i]; $i++) {
                $result[$vars[$i]] = unserialize($vars[++$i]);
            }
        }
        return $result;
    }

    public static function fillNoCacheHoles($html) {
        return preg_replace_callback('/(\<!\-\- +nocache.+?\-\-\>).*?(\<!\-\- endnocache \-\-\>)/si', 'PageCache::replaceNoCacheBlocks', $html);
    }

    public static function replaceNoCacheBlocks($matches) {
        // $matches[0] is the whole block
        // $matches[1] is the <!-- nocache -->
        // $matches[2] is the <!-- endnocache -->
        // print_r($matches);
        $key = self::getAttributeValue('key', $matches[1]);
        if (isset(self::$holeContent[$key])) {
            return self::$holeContent[$key];
        } else {
            return $matches[0];
        }
    }

    public static function getAttributeValue($attribute, $html) {
        preg_match('/(\s*' . $attribute . '=\s*".*?")|(\s*' . $attribute . '=\s*\'.*?\')/', $html, $matches);

        if (count($matches)) {
            $match = $matches[0];
            $match = preg_replace('/ +/', "", $match);
            $match = str_replace($attribute . "=", "", $match);
            $match = str_replace('"', "", $match);
            return $match;
        } else {
            return false;
        }
    }

    public static function sanitizePage($page) {
        $page = preg_replace('/\<!\-\- +nocache.+?\-\-\>/si', "", $page);
        $page = preg_replace('/\<!\-\- endnocache \-\-\>/si', "", $page);
        return $page;
    }

    public static function getCookieLifetime() {
        $lifetime = 3600;
        try {
            $sql = "SELECT value FROM core_config_data WHERE path = 'web/cookie/cookie_lifetime' AND scope = 'default' AND scope_id = 0";
            $result = mysqli_query(self::$database, $sql);
            while ($row = mysqli_fetch_array($result)) {
                if (isset($row[0])) {
                    $lifetime = (int) $row[0];
                }
            }
        } catch (Exception $e) {
            
        }

        return $lifetime;
    }

    public static function report($message, $term=false) {
        if (self::$debugMode) {
            echo "$message<br />";
            if ($term) {
                exit;
            }
        }
    }

    public static function prepareDebugger() {
        if (isset($_GET['debug_front']) && $_GET['debug_front'] == '1') {
            self::$debugMode = true;
        }
    }

    public static function verifyConfigurationExists() {
        if (!file_exists('app/etc/local.xml')) {
            throw new Exception('cannot find local.xml at app/etc/local.xml');
        }
    }

    public static function loadConfiguration() {
        $config = simplexml_load_file('app/etc/local.xml');
        $nodeFound = false;
        foreach ($config->children() as $child) {
            if ($child->getName() == 'lightspeed') {
                $nodeFound = true;
                foreach ($child->children() as $child2) {
                    switch ($child2->getName()) {
                        case 'global':
                            self::report("found the global db node");
                            self::$database = mysqli_connect((string) $child2->connection->host, (string) $child2->connection->username, (string) $child2->connection->password);
                            mysqli_select_db(self::$database, (string) $child2->connection->dbname);

                            self::$request_path = (string) $child2->request_path;
                            self::$request_path = rtrim(trim(self::$request_path), '/');
                            if ($child2->multi_currency) {
                                self::$multiCurrency = (int) $child2->multi_currency;
                            }
                            break;
                        case 'session':
                            switch ((string) $child2->type) {
                                case 'memcached':
                                    // self::report("Session store is memcached");
                                    if (!class_exists('Memcache')) {
                                        throw new Exception('Memcache extension not installed, but configured for use in local.xml');
                                    }
                                    self::$sessionType = 'memcached';
                                    self::$sessionConfig['server'] = new Memcache();
                                    foreach ($child2->servers->children() as $server) {
                                        self::$sessionConfig['server']->addServer(
                                                (string) $server->host
                                                , (int) $server->port
                                                , (bool) $server->persistant
                                        );
                                    }
                                    break;
                                case 'db':
                                    // self::report("session store is db");
                                    self::$sessionType = 'db';
                                    self::$sessionConfig['connection'] = mysqli_connect((string) $child2->connection->host, (string) $child2->connection->username, (string) $child2->connection->password);
                                    mysqli_select_db(self::$sessionConfig['connection'], (string) $child2->connection->dbname);
                                    break;
                                case 'files':
                                default:
                                    // self::report("session store is files");
                                    self::$sessionType = 'files';
                                    self::$sessionConfig['path'] = (string) $child2->path;
                                    if (!self::$sessionConfig['path']) {
                                        self::$sessionConfig['path'] = 'var/session';
                                    }
                                    break;
                            }
                            break;
                        case 'cache':
                            switch ((string) $child2->type) {
                                case 'memcached':
                                    // self::report("cache engine is memcached");
                                    if (!class_exists('Memcache')) {
                                        throw new Exception('Memcache extension not installed, but configured for use in local.xml');
                                    }
                                    self::$cacheEngine = 'memcached';
                                    self::$cacheData['server'] = new Memcache();
                                    foreach ($child2->servers->children() as $server) {
                                        self::$cacheData['server']->addServer(
                                                (string) $server->host
                                                , (int) $server->port
                                                , (bool) $server->persistant
                                        );
                                    }
                                    break;
                                case 'files':
                                default:
                                    // self::report("cache engine is files");
                                    self::$cacheEngine = 'files';
                                    self::$cacheData['path'] = (string) $child2->path;
                                    if (!self::$cacheData['path']) {
                                        self::$cacheData['path'] = 'var/lightspeed';
                                    }
                                    break;
                            }
                            break;
                    }
                }
            }
        }

        if (!$nodeFound) {
            throw new Exception("local.xml does not contain <lightspeed> node");
        }
    }

    public static function renderCachedPage() {
        if ($page = self::getCachedPage()) {
            self::report("success!, I'm about to spit out a cached page, look out.", true);
            self::prepareHeaders();
            echo self::sanitizePage($page);
        } else {
            throw new Exception("no cache matches at this url.");
        }
    }

    public static function prepareHeaders() {
        header("Pragma: no-cache");
        header("Cache-Control: no-cache, must-revalidate, no-store, post-check=0, pre-check=0");
    }

    public static function getStoreCode() {
        if (!self::$storeCode) {
            if (!self::getSession()) {
                self::report("session data is false, setting store code to: store_default");
                self::$storeCode = 'store_default';
            } else {
                foreach (array_keys(self::getSession()) as $_key) {
                    if (substr($_key, 0, 5) == 'store') {
                        self::$storeCode = $_key;
                        self::report("found a match in the session data for store code, setting store code to: $_key");
                        break;
                    }
                }

                self::$storeCode = 'store_default';
                self::report("setting store code to: store_default");
            }
        }
        return self::$storeCode;
    }

    public static function getDefaultCurrencyCode() {
        if (!self::$defaultCurrencyCode) {
            $sql = "SELECT value FROM core_config_data WHERE path = 'currency/options/default'";
            $result = mysqli_query(self::$database, $sql);
            if (count($result)) {
                while ($row = mysqli_fetch_array($result)) {
                    self::$defaultCurrencyCode = $row[0];
                }
            }
        }
        return self::$defaultCurrencyCode;
    }
}
