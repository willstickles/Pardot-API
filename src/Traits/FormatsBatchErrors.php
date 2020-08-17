<?php

namespace CyberDuck\PardotApi\Traits;

/**
 * Trait to allow the updating of a specific object by ID in a generic way
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
trait FormatsBatchErrors
{
    protected function formatBatchErrors($data)
    {
        return "Pardot API error(s): " . implode(". ", $data["errors"]);
    }
}
