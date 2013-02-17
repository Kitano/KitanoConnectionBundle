<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Exception\InvalidFilterException;
use Kitano\ConnectionBundle\Model\ConnectionInterface;

class FilterValidator
{
    private $allowedFilters = array(
        'type' => array(
            'constraints' => array(
                'NotNull'
            ),
            'normalize' => true
        ),
        'status' => array(
            'allowed_values' => array(
                ConnectionInterface::STATUS_CONNECTED,
                ConnectionInterface::STATUS_DISCONNECTED
            ),
        ),
        'depth' => array(
            'constraints' => array(
                'integer'
            ),
            'default' => 1
        ),
    );

    /**
     * Validate and normalize input filters for connections retrieval
     *
     * @param array &$filters
     * @throws InvalidFilterException
     */
    public function validateFilters(array &$filters)
    {
        $filters = array_intersect_key($filters, $this->allowedFilters);

        if(!empty($filters)) {
            foreach($this->allowedFilters as $filterName => $rules)
            {
                //test defaults values
                if(array_key_exists('default', $rules) && !array_key_exists($filterName, $filters))
                {
                    if(!array_key_exists('default', $filters)) {
                        $filters[$filterName] = $rules['default'];
                    }
                }

                //test constraints
                if(array_key_exists('constraints', $rules))
                {
                    foreach($rules['constraints'] as $constraint)
                    {
                        switch($constraint)
                        {
                            case 'NotNull' :
                                if(!array_key_exists($filterName, $filters)) {
                                    throw new InvalidFilterException(
                                        sprintf("Filter parameter %s must not be null.", $filterName)
                                    );
                                }
                                break;

                            case 'integer' :
                                if(!is_integer($filters[$filterName])) {
                                    throw new InvalidFilterException(
                                        sprintf("Filter parameter %s must be an integer.", $filterName)
                                    );
                                }
                                break;
                        }
                    }
                }

                if(!array_key_exists($filterName, $filters)) {
                    continue;
                }

                // test allowed values
                if(array_key_exists($filterName, $filters) && array_key_exists('allowed_values', $rules))
                {
                    if(!in_array($filters[$filterName], $rules['allowed_values'], true)) {
                        throw new InvalidFilterException(
                            sprintf("Filter value %s is not allowed for the %s parameter.", $filters[$filterName], $filterName)
                        );
                    }
                }

                //normalization : if scalar, transform to array
                if(array_key_exists('normalize', $rules) && $rules['normalize'] == true)
                {
                    if(is_scalar($filters[$filterName])) {
                        $filters[$filterName] = array($filters[$filterName]);
                    }
                }
            }
        } else {
            throw new InvalidFilterException("Error in connection filters. No good filter is matching. Please check your method call.");
        }
    }
}