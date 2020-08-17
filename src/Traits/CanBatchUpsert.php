<?php

    namespace CyberDuck\PardotApi\Traits;

    trait CanBatchUpsert
    {
        public function batchUpsert(array $data)
        {
            return $this
                ->setOperator('batchUpsert')
                ->setJson($this->jsonKey, [$this->jsonKey => $data])
                ->request($this->object);
        }
    }
