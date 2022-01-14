<?php

namespace Elemes\DataPrivacy\Api;

interface CustomInterface
{
     /**
    * GET for Post api
    * @param mixed $value
    * @return string
    */
    public function setData();
    /**
     * GET for Post api
     * @return string
     */
    public function getConfigPrivacy();
    /**
     * GET Data customer
     * @param string $customerId
     * @return string
     */
    public function getData($customerId);
}
