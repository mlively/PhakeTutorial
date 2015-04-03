<?php
namespace PhakeStore;

class ItemAvailabilityService
{
    /**
     * Returns whether or not a particular item is available
     *
     * @param string $itemSku
     * @return boolean
     */
    public function isItemAvailable($itemSku)
    {
        if ($itemSku == 'ThisIsATest_inStock')
        {
            return true;
        }
        elseif ($itemSku == 'ThisIsATest_outOfStock')
        {
            return false;
        }

        //do other stuff
    }
}