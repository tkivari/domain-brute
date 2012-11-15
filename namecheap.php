<?php

    namespace DomainBrute;
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/siesta/restclient.php');
    
    class namecheap {
        
        private $config = array(
            'host'  => 'https://api.namecheap.com/xml.response',
            'method'    => 'GET'
        );
        
        private $api_config = array(
            'ApiUser' => 'username',
            'UserName' => 'username',
            'ApiKey' => 'XXXXXXXXXXXXXXXXXXXXX',
            'ClientIp'  => '192.168.1.1'
        );
        
        private $restclient;
        
        private $response;
        
        private $available_domains = array();
        
        public function __construct($config = array()) {
            $this->config = array_merge($this->config,$config);
            
            if (class_exists('\Siesta\restclient')) {
                $this->restclient = new \Siesta\restclient();
            }
            else {
                throw new \Exception("REST Client not found.  Download from https://www.github.com/tkivari/Siesta");
            }            

        }
        
        public function execute($api_config = array()) {
            
            $this->available_domains = array();
            
            $this->api_config = array_merge($this->api_config,$api_config);
            
            $this->url = self::NameCheapURL($this->config['host'], $this->api_config);
            
            if (method_exists('\Siesta\restclient',strtolower($this->config['method']))) {
                $xml = $this->restclient->{strtolower($this->config['method'])}($this->url);
                $doc = new \SimpleXMLElement($xml);
                
                $this->response = $doc->CommandResponse;
                $this->parse_result();
                
            }
            else {
                throw new \Exception("Oh no!  The rest client doesn't support the HTTP " . $this->config['method'] . " method!");
            }
        }
        
        private function parse_result() {
            foreach($this->response->DomainCheckResult as $domain) {
                if ((string) $domain["Available"] == "true") { $this->available_domains[] = (string) $domain["Domain"]; }
            }
        }
        
        public static function NameCheapURL($host, $api_config) {
            $url = $host . '?';
            
            $keys = array();
            foreach($api_config as $k => $v) {
                $keys[] = $k.'='.$v;
            }
            $url .= implode("&",$keys);
            return $url;
        }
        
        public function get_available_domains() {
            return $this->available_domains;
        }
        
    }

?>
