<?php

  namespace CyberDuck\Pardot;

  use GuzzleHttp\Exception\ClientException;

  class PardotExceptionHandler
  {
    protected $exception, $pardotErrorMessage;

    public function __construct(\Exception $exception)
    {
      $this->exception = $exception;
    }

    /**
     * Extracts Pardot Error Response
     *
     * @throws \Exception
     */
    protected function extractErrorInfo()
    {
      // Rethrow exception if it's something unexpected.
      if (!is_a($this->exception, ClientException::class)) {
        throw new \PardotApiException($this->exception->getMessage());
      }

      $this->pardotErrorMessage = json_decode( $this->exception->getResponse()->getBody()->getContents(), true );

      // Rethrow exception if it's something unexpected.
      if (empty($this->pardotErrorMessage["@attributes"]["err_code"])) {
        throw new \PardotApiException($this->exception->getMessage());
      }
    }

    /**
     * @throws \Exception
     */
    public function __invoke()
    {
      $this->extractErrorInfo();

      // Do something...

      throw new \PardotApiException($this->exception->getMessage());
    }
  }