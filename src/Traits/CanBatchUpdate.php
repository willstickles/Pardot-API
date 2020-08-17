<?php

    namespace CyberDuck\PardotApi\Traits;

    trait CanBatchUpdate
    {
        public function batchUpdate(array $data)
        {
            return $this
                ->setOperator('batchUpdate')
                ->setJson($this->jsonKey, [$this->jsonKey => $data])
                ->request($this->object);
        }
    }
