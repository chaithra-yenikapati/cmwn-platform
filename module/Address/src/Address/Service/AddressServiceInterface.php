<?php


namespace Address\Service;

use Address\AddressInterface;
use Application\Exception\NotFoundException;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Interface AddressServiceInterface
 * @package Address\Service
 */
interface AddressServiceInterface
{
    /**
     * @param string $addressId
     * @param AddressInterface|null $prototype
     * @return AddressInterface
     * @throws NotFoundException
     */
    public function fetchAddress(string $addressId, AddressInterface $prototype = null) : AddressInterface;

    /**
     * @param null $where
     * @param AddressInterface|null $prototype
     * @return AdapterInterface
     */
    public function fetchAll($where = null, AddressInterface $prototype = null) : AdapterInterface;

    /**
     * @param AddressInterface $address
     * @return bool
     */
    public function createAddress(AddressInterface $address) : bool;

    /**
     * @param AddressInterface $address
     * @return bool
     */
    public function updateAddress(AddressInterface $address) : bool;

    /**
     * @param AddressInterface $address
     * @return bool
     */
    public function deleteAddress(AddressInterface $address) : bool;
}
