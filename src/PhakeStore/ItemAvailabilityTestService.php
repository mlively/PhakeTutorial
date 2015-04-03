<?php
namespace PhakeStore;

class ItemAvailabilityTestService extends ItemAvailabilityService {

    private $fakeStockStatus;

    public function __construct($fakeStockStatus)
    {
        $this->fakeStockStatus = (bool)$fakeStockStatus;
    }

    public function isItemAvailable($itemSku)
    {
        return $this->fakeStockStatus;
    }


}