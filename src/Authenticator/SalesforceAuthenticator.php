<?php

  namespace CyberDuck\Pardot\Authenticator;

  use CyberDuck\PardotApi\Contract\PardotApi;
  use CyberDuck\PardotApi\Contract\PardotAuthenticator as PardotAuthenticatorInterface;
  use Exception;
  use GuzzleHttp\Psr7\Response;
  use GuzzleHttp\RequestOptions;
  use Stevenmaguire\OAuth2\Client\Provider\Salesforce;

  /**
   * Salesforce OAUTH/Pardot API Authenticator
   *
   */
  class SalesforceAuthenticator implements PardotAuthenticatorInterface
  {
    /**
     * Main API class instance
     *
     * @var PardotApi
     */
    protected $api;

    /**
     * Auth user email credential
     *
     * @var string
     */
    protected $email;

    /**
     * Auth user password credential
     *
     * @var string
     */
    protected $password;

    /**
     * Returned request API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Business Unit ID begins with "0Uv" and is 18 characters long.
     * See this page for more details.
     * https://developer.pardot.com/kb/authentication/
     */
    protected $businessUnitId;

    /**
     * Consumer Key.
     * You must create a connected SF App with "pardot_api" scope.
     * See this page for more details.
     * https://developer.pardot.com/kb/authentication/
     */
    protected $consumerKey;

    /**
     * Consumer Secret.
     * You must create a connected SF App with "pardot_api" scope.
     * See this page for more details.
     * https://developer.pardot.com/kb/authentication/
     */
    protected $consumerSecret;

    /**
     * SF User Api Security Token.
     * This must be added to the end of the user password,
     * as a part of the password oauth flow.
     *
     */
    protected $userApiSecurityToken;

    /**
     * OAUTH Grant type.
     * For now, this package only supports 'password'.
     */
    protected $grantType = "password";

    /**
     * Returns response
     *
     * @var Response|null
     */
    protected $response;

    /**
     * Flag to indicate the authentication request has been sent
     *
     * @var boolean
     */
    protected $authenticated = false;

    /**
     * Flag to indicate the authentication request has been successful
     *
     * @var boolean
     */
    protected $success = false;

    /**
     * Sets the required APi credentials
     *
     * @param PardotApi $api
     * @param string $email
     * @param string $password
     * @param string|null $userApiSecurityToken
     * @param string|null $businessUnitId
     * @param string|null $consumerKey
     * @param string|null $consumerSecret
     */
    public function __construct(
      PardotApi $api, string $email, string $password,
      string $userApiSecurityToken, string $businessUnitId,
      string $consumerKey, string $consumerSecret
    )
    {
      $this->api = $api;
      $this->email = $email;
      $this->password = $password;
      $this->userApiSecurityToken = $userApiSecurityToken;
      $this->businessUnitId = $businessUnitId;
      $this->consumerKey = $consumerKey;
      $this->consumerSecret = $consumerSecret;
    }

    /**
     * Returns the user credential key for use in query requests
     *
     * @return string
     */
    public function getUserKey(): string
    {
      return $this->userKey;
    }

    /**
     * Performs the login authentication request to return and set the API key
     *
     * @throws Exception
     */
    public function doAuthentication()
    {
      try {
        $this->authenticated = true;
        $this->success = true;

        $provider = new Salesforce([
          'clientId' => $this->consumerKey,
          'clientSecret' => $this->consumerSecret
        ]);

        if ($this->grantType != 'password') {
          throw new \Exception("'{$this->grantType}' is not a supported grant type");
        }

        $this->apiKey = $provider->getAccessToken($this->grantType, [
          'username' => $this->email,
          'password' => $this->password . $this->userApiSecurityToken
        ]);
      } catch (Exception $e) {
        if ($this->api->getDebug() === true) {
          throw new Exception("Unable to authenticate with SF OAuth. Check credentials.");
        }
      }
    }

    /**
     * Returns the Response object or null on failure
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
      return $this->response;
    }

    /**
     * Returns whether the login authentication request has been attempted
     *
     * @return boolean
     */
    public function isAuthenticated(): bool
    {
      return $this->authenticated;
    }

    /**
     * Returns whether the login authentication request has been successful
     *
     * @return boolean
     */
    public function isAuthenticatedSuccessfully(): bool
    {
      return $this->success;
    }

    /**
     * Returns the API key returned from the login request for use in query requests
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
      return $this->apiKey;
    }

    /**
     * Gets Pardot Business Unit Id for use in query requests
     *
     * @return string|null
     */
    public function getBusinessUnitId() :?string
    {
      return $this->businessUnitId;
    }

    /**
     * Sets Pardot Business Unit Id for use in query requests
     *
     * @param string $businessUnitId
     * @return bool
     */
    public function setBusinessUnitId(string $businessUnitId) : bool
    {
      return $this->businessUnitId = $businessUnitId;
    }

    /**
     * Returns request options needed with respect to auth type
     *
     * @return array[]
     */
    public function getHeaderRequestOptions() : array
    {
      return [RequestOptions::HEADERS => [
        'Authorization' => sprintf('Bearer %s', $this->getApiKey()),
        'Pardot-Business-Unit-Id' => $this->getBusinessUnitId()
      ]];
    }
  }
