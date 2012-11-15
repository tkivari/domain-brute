<?php

    namespace DomainBrute;

    class domain_generator {

        private $config = array(
            'valid_chars' => 'abcdefghijklmnopqrstuvwxyz1234567890',
            'extensions' => array('com'),
            'min_length' => 3,
            'max_length' => 3
            
        );
        
        private $permutations = array();
        private $perms = array();
        private $domains = array();
        private $domain_list;
        
        public function __construct($config = array()) {
            $this->config = array_merge($this->config,$config);
            $this->compute();
        }
        
        private function compute() {

            $perms = array();
            for ($i=$this->config['min_length']; $i<=$this->config['max_length']; ++$i) {
                $this->calculate_permutations($i,0);
            }
            
            foreach ($this->config['extensions'] as $ext) {
                foreach ($this->permutations as $perm) {
                    $this->domains[] = $perm . '.' . $ext;
                }
            }
            
            $this->domain_list = implode(",",$this->domains);

        }
        
        private function calculate_permutations($length,$pos,$out='') {
            
            for ($i=0;$i<strlen($this->config['valid_chars']);++$i) {
                if ($pos < $length) {
                    $this->calculate_permutations($length, $pos + 1, $out . $this->config['valid_chars'][$i]);
                }
            }
            
            if (strlen($out) >= $this->config['min_length'] && strlen($out) <= $this->config['max_length'])
                $this->permutations[] = $out;

        }
        
        public function get_domains() {
            return $this->domains;
        }
        
        public function get_domain_list() {
            return $this->domain_list;
        }
        
        
    }


?>
