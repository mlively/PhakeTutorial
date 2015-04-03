<?php
namespace PhakeStore;

use Phake;

class CartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @Mock
     * @var \PhakeStore\ItemAvailabilityService
     */
    private $itemService;

    /**
     * @Mock
     * @var \PhakeStore\EventService
     */
    private $eventService;

    public function setUp()
    {
        Phake::initAnnotations($this);
        Phake::when($this->itemService)->isItemAvailable->thenReturn(true);

        $this->cart = new Cart($this->itemService, $this->eventService);
    }

    public function testAddItemToCart()
    {
        //Setup
        $itemSku = "12345";

        //Exercise
        $this->cart->addItem($itemSku);

        //Verify
        $this->assertEquals(array("12345"), $this->cart->getItems());
        Phake::verify($this->itemService)->isItemAvailable("12345");

        //TearDown
    }

    public function testAddOutOfStockItemToCart()
    {
        //Setup
        Phake::when($this->itemService)->isItemAvailable->thenReturn(false);
        $itemSku = "12345";
        $this->setExpectedException('Exception');

        //Exercise
        $this->cart->addItem($itemSku);

        //Verify
        //TearDown
    }

    public function testAddingMultipleItemsToCart()
    {
        //Setup
        $items = [ "12345", "67890", "abcdefg" ];

        //Exercise
        $this->cart->addMultipleItems($items);

        //Verify
        $this->assertEquals($items, $this->cart->getItems());
        Phake::verify($this->itemService)->isItemAvailable("abcdefg");
        Phake::verify($this->itemService)->isItemAvailable("67890");
        Phake::verify($this->itemService)->isItemAvailable("12345");

        //TearDown
    }

    public function testItemAvailaibilityEventsFire()
    {
        //Setup
        $itemSku = "12345";

        //Exercise
        $this->cart->addItem($itemSku);

        //Verify
        $this->assertEquals(array("12345"), $this->cart->getItems());
        Phake::inOrder(
            Phake::verify($this->eventService)->fireEvent("availabilitycheck.before", Phake::capture($beforeContext)),
            Phake::verify($this->itemService)->isItemAvailable("12345"),
            Phake::verify($this->eventService)->fireEvent("availabilitycheck.after", Phake::capture($afterContext))
        );

        $this->assertEquals(array("item" => "12345"), $beforeContext);
        $this->assertEquals(array("item" => "12345", "available" => true), $afterContext);

        //TearDown
    }
}