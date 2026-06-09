<?php
class ExpLdap{
    private $server;
    private $userId;
    private $dn,$ds,$rs;
    private $suf;

    function __construct($srv, $dn, $domain){
        $this->server = "ldap://{$srv}";
        $this->suf = $domain;
        $this->dn = $dn;
        $this->ds = ldap_connect($this->server);

        if (!$this->ds) {
            throw new Exception("Unable to connect to the Directory server");
        }
    }
    protected function search_user(){
        $res = ldap_search($this->ds, $this->dn, "sAMAccountName={$this->userId}");
        // $res = ldap_search($this->ds, $this->dn, "sAMAccountName=anuradhani");
        $data = ldap_get_entries ($this->ds, $res);
        $user="";
        if(isset($data[0]["sn"][0])) $user = $data[0];
       // print_r($data[0]);

        if($user=="") return false;
        else return $user;
    }

    public function user_auth($uname,$pwd){
        $this->userId=$uname;
        $this->rs=ldap_bind($this->ds,"{$uname}@{$this->suf}",$pwd);
        if($this->rs){
           $user=$this->search_user();
        //    print_r($user);
        // echo $user['CN'][0];
        // print_r($user['distinguishedname'][0]);
           if(!$user) return array("loginStatus"=>false,"msg"=>"User not found");
           else return array(
               "loginStatus"=>true,
               "kelaniNetId"=>$user['sn'][0],
               "empcode"=>$user["physicaldeliveryofficename"][0],
               "phone"=>isset($user['mobile'][0]) ? $user['mobile'][0] : null,
               "'distinguishedname'"=>$user['distinguishedname'][0]
           );
        }else{
            return array("state"=>false,"msg"=>"Invalid Credentials");
        }
    }

}

?>