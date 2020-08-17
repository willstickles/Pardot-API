<?php

    namespace CyberDuck\PardotApi\Formatter;

    use CyberDuck\PardotApi\Traits\FormatsBatchErrors;
    use Exception;
    use stdClass;

    /**
     * JSON Formatter
     *
     * @category   PardotApi
     * @package    PardotApi
     * @author     Andrew Mc Cormack <andy@cyber-duck.co.uk>
     * @copyright  Copyright (c) 2018, Andrew Mc Cormack
     * @license    https://github.com/cyber-duck/pardot-api/license
     * @version    1.0.0
     * @link       https://github.com/cyber-duck/pardot-api
     * @since      1.0.0
     */
    class JsonFormatter
    {
        use FormatsBatchErrors;

        /**
         * Input data
         *
         * @var string
         */
        private $data;

        /**
         * Required property name
         *
         * @var string
         */
        private $property;

        /**
         * Sets the required properties
         *
         * @param string $data
         * @param string $property
         */
        public function __construct(string $data, string $property)
        {
            $this->data = $data;
            $this->property = $property;
        }

        /**
         * Returns the formatted output data
         *
         * @return array|boolean
         */
        public function getData()
        {
            $data = json_decode($this->data, true);

            if (!empty($data["err"])) {
                throw new Exception(sprintf('Pardot API error: %s', $data["err"]));
            }
            if (!empty($data["errors"])) {
                throw new Exception($this->formatBatchErrors($data));
            }
            // If this is a batch request, there's no return data... so just check if it's 'ok'
            if (!empty($data[$this->property]) && $data["@attributes"]["stat"] !== "ok") {
                throw new Exception(sprintf('Pardot API error: cannot find %s in response', $this->property));
            }
            return $data ?? true;
        }
    }
