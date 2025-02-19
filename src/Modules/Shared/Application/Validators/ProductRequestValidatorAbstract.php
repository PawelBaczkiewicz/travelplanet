<?php

declare(strict_types=1);

namespace Modules\Shared\Application\Validators;

use Illuminate\Support\Facades\Validator;
use Modules\Shared\Application\DTOs\RequestProductDTOInterface;
use Ramsey\Uuid\Uuid;

abstract class ProductRequestValidatorAbstract
{
    protected bool $isValidated;
    protected array $validatedData;
    public function __construct()
    {
        $this->isValidated = false;
        $this->validatedData = [];
    }

    abstract protected function getDTOClass(): string;
    abstract protected function rules(): array;
    abstract protected function messages(): array;

    public function validate(): self
    {
        $validator = Validator::make(request()->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->toArray(), 'Request Validation failed');
        }

        $this->isValidated = $validator->passes();
        $this->validatedData = $validator->validated();

        return $this;
    }

    public function getValidatedDTO(): RequestProductDTOInterface
    {
        $this->ensureValidated();

        $dtoData = [];
        foreach ($this->validatedData as $key => $value) {
            $dtoData[$key] = $this->castValueToDTOValue($key, $value);
        }

        $request = request();
        $productId = $request->route('product');
        if ($request->isMethod('put') && $productId) {

            if (Uuid::isValid($productId)) {
                $dtoData['id'] = Uuid::fromString($productId);
            } else {
                throw new ValidationException(['id' => 'Invalid UUID format for product ID.'], 'Request Validation failed');
            }
        }

        $dtoClass = $this->getDTOClass();

        return $dtoClass::createFromArray($dtoData);
    }

    protected function getValidatedObjectId(): string
    {
        return $this->validatedData['id'];
    }

    protected function ensureValidated(): void
    {
        if (!$this->isValidated) {
            throw new \LogicException('Data must be validated before accessing. Call validate() first.');
        }
    }

    protected function getValueCasting(): array
    {
        return [];
    }

    protected function castValueToDTOValue($key, $value): mixed
    {
        $mapping = $this->getValueCasting();

        $type = $mapping[$key] ?? 'string';

        switch ($type) {
            case 'float':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'date_iso8601':
                return (new \DateTime(
                    $value,
                    new \DateTimeZone($this->validatedData['user_timezone'] ?? 'UTC')
                ))->format('Y-m-d\TH:i:sP');
            default:
                return $value;
        }
    }
}
