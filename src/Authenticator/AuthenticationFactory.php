<?php

  namespace CyberDuck\Pardot\Authenticator;

  use CyberDuck\Pardot\PardotApi;

  class AuthenticationFactory
  {
    protected $api,$email,$password,$userKey,$authType,$userApiSecurityToken,$businessUnitId,$consumerKey,$consumerSecret;

    public function __construct(
      PardotApi $api, string $authType, string $email, string $password, string $userApiSecurityToken,
      string $userKey = null, string $businessUnitId = null, string $consumerKey = null,
      string $consumerSecret = null
    )
    {
      $this->api = $api;
      $this->authType = $authType;
      $this->email = $email;
      $this->password = $password;
      $this->userKey = $userKey;
      $this->userApiSecurityToken = $userApiSecurityToken;
      $this->businessUnitId = $businessUnitId;
      $this->consumerKey = $consumerKey;
      $this->consumerSecret = $consumerSecret;
    }

    public function __invoke()
    {
      if ($this->authType == "PARDOT") {
        return new PardotAuthenticator(
          $this->api, $this->email, $this->password, $this->userKey
        );
      }
      if ($this->authType == "OAUTH") {
        return new SalesforceAuthenticator(
          $this->api, $this->email, $this->password, $this->userApiSecurityToken,
          $this->businessUnitId, $this->consumerKey, $this->consumerSecret
        );
      }
      throw new \Exception("{$this->authType} is an unsupported authentication type");
    }
  }
