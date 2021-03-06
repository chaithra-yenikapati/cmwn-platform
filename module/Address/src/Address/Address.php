<?php

namespace Address;

use Zend\Filter\StaticFilter;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * Class Address
 */
class Address implements AddressInterface
{
    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $addressId;

    /**
     * @var string
     */
    protected $administrativeArea;

    /**
     * @var string
     */
    protected $subAdministrativeArea;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @var string
     */
    protected $dependentLocality;

    /**
     * @var string
     */
    protected $postalCode;

    /**
     * @var string
     */
    protected $thoroughfare;

    /**
     * @var string
     */
    protected $premise;

    /**
     * Address constructor.
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->exchangeArray($array);
    }

    /**
     * @inheritdoc
     */
    public function exchangeArray(array $array)
    {
        $defaults = [
            'country'                 => null,
            'address_id'              => null,
            'administrative_area'     => null,
            'sub_administrative_area' => null,
            'locality'                => null,
            'dependent_locality'      => null,
            'postal_code'             => null,
            'thoroughfare'            => null,
            'premise'                 => null,
        ];

        $array = array_merge($defaults, $array);

        foreach ($array as $key => $value) {
            $method = 'set' . ucfirst(StaticFilter::execute($key, UnderscoreToCamelCase::class));
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getArrayCopy()
    {
        return [
            'country'                 => $this->getCountry(),
            'address_id'              => $this->getAddressId(),
            'administrative_area'     => $this->getAdministrativeArea(),
            'sub_administrative_area' => $this->getSubAdministrativeArea(),
            'locality'                => $this->getLocality(),
            'dependent_locality'      => $this->getDependentLocality(),
            'postal_code'             => $this->getPostalCode(),
            'thoroughfare'            => $this->getThoroughfare(),
            'premise'                 => $this->getPremise(),
        ];
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country = null)
    {
        $this->country = (string) $country;
    }

    /**
     * @return string
     */
    public function getAddressId() : string
    {
        return $this->addressId;
    }

    /**
     * @param string $addressId
     */
    public function setAddressId(string $addressId = null)
    {
        $this->addressId = (string) $addressId;
    }

    /**
     * @return string
     */
    public function getAdministrativeArea() : string
    {
        return $this->administrativeArea;
    }

    /**
     * @param string $administrativeArea
     */
    public function setAdministrativeArea(string $administrativeArea = null)
    {
        $this->administrativeArea = (string) $administrativeArea;
    }

    /**
     * @return string
     */
    public function getSubAdministrativeArea() : string
    {
        return $this->subAdministrativeArea;
    }

    /**
     * @param string $subAdministrativeArea
     */
    public function setSubAdministrativeArea(string $subAdministrativeArea = null)
    {
        $this->subAdministrativeArea = (string) $subAdministrativeArea;
    }

    /**
     * @return string
     */
    public function getLocality() : string
    {
        return $this->locality;
    }

    /**
     * @param string $locality
     */
    public function setLocality(string $locality = null)
    {
        $this->locality = (string) $locality;
    }

    /**
     * @return string
     */
    public function getDependentLocality() : string
    {
        return $this->dependentLocality;
    }

    /**
     * @param string $dependentLocality
     */
    public function setDependentLocality(string $dependentLocality = null)
    {
        $this->dependentLocality = (string) $dependentLocality;
    }

    /**
     * @return string
     */
    public function getPostalCode() : string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode = null)
    {
        $this->postalCode = (string) $postalCode;
    }

    /**
     * @return string
     */
    public function getThoroughfare() : string
    {
        return $this->thoroughfare;
    }

    /**
     * @param string $thoroughfare
     */
    public function setThoroughfare(string $thoroughfare = null)
    {
        $this->thoroughfare = (string) $thoroughfare;
    }

    /**
     * @return string
     */
    public function getPremise() : string
    {
        return $this->premise;
    }

    /**
     * @param string $premise
     */
    public function setPremise(string $premise = null)
    {
        $this->premise = (string) $premise;
    }
}
