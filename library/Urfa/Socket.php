<?php

class Urfa_Socket
{
    private $sock = NULL;
    private $host;
    private $port;

    public function open($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $context = stream_context_create();
        stream_context_set_option($context,'ssl','ciphers','ADH-RC4-MD5');
        stream_context_set_option($context,'ssl','verify_peer_name',false);
        stream_context_set_option($context,'ssl','verify_peer',false);
        $this->sock = @stream_socket_client("tcp://[".$host."]:".$port, $errno,$errstr,5,STREAM_CLIENT_CONNECT,$context);
        if(!$this->sock) {
            $this->sock = @stream_socket_client("tcp://".$host.":".$port,$errno,$errstr,5,STREAM_CLIENT_CONNECT,$context);
            if($this->sock)
                return true;
            return false;
        }
        return true;
    }

    public function close()
    {
        if ($this->sock) {
            fclose($this->sock);
            $this->sock = NULL;
        }
        return TRUE;
    }

    public function read($count)
    {
        return fread($this->sock, $count);
    }

    public function write($var)
    {
        return fwrite($this->sock, $var);
    }

    public function enable_crypto($method)
    {
        stream_socket_enable_crypto($this->sock, true, $method);
    }

}
