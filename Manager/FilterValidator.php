<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Exception\InvalidFilterException;

use Symfony\Component\Validator\Validator;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Collection;

class FilterValidator
{
    protected $validator;

    /**
     * Validate and normalize input filters for connections retrieval
     *
     * @param array &$filters
     * @throws InvalidFilterException
     */
    public function validateFilters(array &$filters)
    {
        $filterConstraint = new Collection(array(
            'type' => array(
                new NotBlank(),
                new NotNull(),
            ),
            'depth' => new Type('integer'),
        ));

        $filtersDefault = array(
            'depth' => 1,
        );

        $filters = array_merge($filtersDefault, $filters);

        $errorList = $this->getValidator()->validateValue($filters, $filterConstraint);

        if (count($errorList) == 0) {
            return true;
        } else {
            throw new InvalidFilterException($errorList);
        }
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function getValidator()
    {
        return $this->validator;
    }
}
