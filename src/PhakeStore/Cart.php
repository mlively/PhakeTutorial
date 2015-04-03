<?php
namespace PhakeStore;

class Cart {

    private $items = array();

    /**
     * @var ItemAvailabilityService
     */
    private $itemAvailabilityService;

    /**
     * @var EventService
     */
    private $eventService;

    function __construct(ItemAvailabilityService $itemAvailabilityService, EventService $eventService)
    {
        $this->itemAvailabilityService = $itemAvailabilityService;
        $this->eventService = $eventService;
    }

    public function addItem($itemSku)
    {

        $this->eventService->fireEvent('availabilitycheck.before', array("item" => $itemSku));
        $inStock = $this->itemAvailabilityService->isItemAvailable($itemSku);
        $this->eventService->fireEvent('availabilitycheck.after', array("item" => $itemSku, "available" => $inStock));

        if (!$inStock)
        {
            throw new \Exception("Out of Stock!");
        }

        $this->items[] = $itemSku;
    }

    public function addMultipleItems(array $itemSkus)
    {
        foreach ($itemSkus as $itemSku)
        {
            $this->addItem($itemSku);
        }
    }

    public function getItems()
    {
        return $this->items;
    }
}