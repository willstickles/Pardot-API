<?php

    namespace CyberDuck\PardotApi\Traits;

    /**
     * Trait to allow the creation of specific object types in a generic way
     *
     * Implementing classes should have a $object property which has the particular
     * object type e.g campaign
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
    trait CanBatchCreate
    {
        /**
         * Sends the request to create the new object and returns it from the API
         *
         * /api/{object}/version/{version}/do/create?...
         *
         * required: user_key, api_key, ...others depend on object type
         *
         * @param array $data
         *
         * @return array|boolean
         */
        public function batchCreate(array $data)
        {
            return $this
                ->setOperator('batchCreate')
                ->setJson($this->jsonKey, [$this->jsonKey => $data])
                ->request($this->object);
        }
    }
