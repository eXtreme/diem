<?php

abstract class dmInitFilter extends dmFilter
{
  
  protected function checkFilesystemPermissions()
  {
    return dmProject::checkFilesystemPermissions();
  }
  
  protected function saveApplicationUrl()
  {
    if(sfConfig::get('sf_environment') === 'test')
    {
      return;
    }
    
    $knownBaseUrls = json_decode(dmConfig::get('base_urls', '[]'), true);
    
    $appUrlKey = implode('-', array(sfConfig::get('sf_app'), sfConfig::get('sf_environment')));
    
    $appUrl    = $this->request->getUriPrefix().$this->context->getRequest()->getScriptName();
      
    if (!isset($knownBaseUrls[$appUrlKey]) || $knownBaseUrls[$appUrlKey] !== $appUrl)
    {
      $knownBaseUrls[$appUrlKey] = $appUrl;
      dmConfig::set('base_urls', json_encode($knownBaseUrls));
    }
  }

  protected function redirectTrailingSlash()
  {
    $uri = $this->request->getUri();
    
    if ('/' === substr($uri, -1))
    {
      if ($uri !== ($this->request->getAbsoluteUrlRoot().'/'))
      {
        $this->context->getController()->redirect(rtrim($uri, '/'), 0, 301);
      }
    }
  }

}