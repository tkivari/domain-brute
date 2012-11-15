<?php


    require_once($_SERVER['DOCUMENT_ROOT'] . '/siesta/restclient.php');
    require_once('../domain-brute.php');
    require_once('../namecheap.php');
    
    $db_config = array(
        'extensions' => array('org')
    );
    $domaincheck = new \DomainBrute\domain_generator($db_config);
    
    $config = array(
        'host' => 'https://api.sandbox.namecheap.com/xml.response'
    );
    
    $domains = implode(",",array_slice($domaincheck->get_domains(),15000,1));
    
    echo "Checking Domains: " . $domains . "<br/><br/>";
    
    $api_config = array(
        'ApiUser' => 'tkivari',
        'UserName' => 'tkivari',
        'ApiKey' => 'XXXXXXXX',
        'Command' => 'namecheap.domains.check',
        'DomainList'=> $domains,
        'ClientIp'  => $_SERVER['REMOTE_ADDR']
    );
    
    $namecheap = new \DomainBrute\namecheap($config);
    
    $namecheap->execute($api_config);
    
    echo "Available Domains: <br/><pre>";
    print_r($namecheap->get_available_domains());


?>
