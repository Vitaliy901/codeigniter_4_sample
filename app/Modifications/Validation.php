<?php

namespace App\Modifications;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Validation\Validation as BaseValidation;

class Validation extends BaseValidation
{
    protected array $validatedData;

    public function validate(array $rules, RequestInterface $request): bool
    {
        $validator = $this->setRules($rules)
            ->withRequest($request);

        $this->validatedData = array_intersect_key($this->data,$rules);
        return $validator->run();
    }

    public function validated(): array
    {
        return $this->validatedData;
    }
}