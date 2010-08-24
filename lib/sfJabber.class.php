<?php
class sfJabber
{
  public static function SendMessage($msg, $to)
  {
    if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
    {
      $jabber_server = sfConfig::get('app_sfJabberPlugin_jabber_server');
      $port          = sfConfig::get('app_sfJabberPlugin_port');
      $username      = sfConfig::get('app_sfJabberPlugin_username');
      $host          = sfConfig::get('app_sfJabberPlugin_host');
      $password      = sfConfig::get('app_sfJabberPlugin_password');

      $conn = new XMPPHP_XMPP($jabber_server, $port, $username, $password, 'xmpphp', $host, $printlog=false, $loglevel=XMPPHP_Log::LEVEL_VERBOSE);
      try
      {
        $conn->useSSL();
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->presence();
        $conn->message($to, $msg);
        $conn->disconnect();
      }
      catch(XMPPHP_Exception $e)
      {
      }
    }
  }
}