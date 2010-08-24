<?php
class sfJabber
{
  public static function SendMessage($msg, $to = NULL)
  {
    if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
    {
      $jabber_server = sfConfig::get('app_sfJabberPlugin_jabber_server');
      $port          = sfConfig::get('app_sfJabberPlugin_port');
      $username      = sfConfig::get('app_sfJabberPlugin_username');
      $host          = sfConfig::get('app_sfJabberPlugin_host');
      $password      = sfConfig::get('app_sfJabberPlugin_password');

      if ($to == NULL)
        $to = sfConfig::get('app_sfJabberPlugin_admin');

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

  public static function SendExceptionNotify(sfEvent $event)
  {
    $routing = sfContext::getInstance()->getRouting();
    $uri = $routing->getCurrentInternalUri();

    $text = 'Ошибка: '.$event->getSubject()->getMessage().' по адресу '.$uri;
    sfJabber::SendMessage($text);
  }
}