<?php

namespace Transbank\Onepay;

use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{

    public function testFromJSONThrowsIfParamIsNotJSON()
    {
        $randomString = "definitely not json";
        $this->setExpectedException(\Exception::class, 'Item must be a JSON string or an associative array that is transformable to an associative array using json_decode');
        $item = Item::fromJSON($randomString);
    }

    public function testCreatesAnItemFromJSONString()
    {
        $aJSONStringContainingAnItem = '{"amount": 5000, "quantity": 5, "description": "something valuable"}';
        $item = Item::fromJSON($aJSONStringContainingAnItem);

        /**
         * Correctly creates the item
         */
        $this->assertTrue($item instanceof Item);
        /**
         * Correctly sets the mandatory values
         */
        $this->assertEquals($item->getAmount(), 5000);
        $this->assertEquals($item->getQuantity(), 5);
        $this->assertEquals($item->getDescription(), 'something valuable');
        /**
         * Correctly sets default values
         */
        $this->assertEquals($item->getExpire(), 0);
        $this->assertEquals($item->getAdditionalData(), '');
    }

    public function testCreatesAnItemFromAssociativeArray()
    {
        $aJSONStringContainingAnItem = json_decode('{"amount": 5000, "quantity": 5, "description": "something valuable"}', true);
        $item = Item::fromJSON($aJSONStringContainingAnItem);

        /**
         * Correctly creates the item
         */
        $this->assertTrue($item instanceof Item);
        /**
         * Correctly sets the mandatory values
         */
        $this->assertEquals($item->getAmount(), 5000);
        $this->assertEquals($item->getQuantity(), 5);
        $this->assertEquals($item->getDescription(), 'something valuable');
        /**
         * Correctly sets default values
         */
        $this->assertEquals($item->getExpire(), 0);
        $this->assertEquals($item->getAdditionalData(), '');
    }

    public function testCreatesAnItemFromJSONWithExtraKeys()
    {
        $aJSONStringContainingAnItem = '{"amount": 2000, "quantity": 2, "description": "something else", "useless key": "irrelevant value will be ignored"}';
        $item = Item::fromJSON($aJSONStringContainingAnItem);

        /**
         * Correctly creates the item
         */
        $this->assertTrue($item instanceof Item);
        /**
         * Correctly sets the mandatory values
         */
        $this->assertEquals($item->getAmount(), 2000);
        $this->assertEquals($item->getQuantity(), 2);
        $this->assertEquals($item->getDescription(), 'something else');
        /**
         * Correctly sets default values
         */
        $this->assertEquals($item->getExpire(), 0);
        $this->assertEquals($item->getAdditionalData(), '');
    }

    public function testCreatesAnItemFromJSONWithOptionalValues()
    {
        $aJSONStringContainingAnItem = '{"amount": 2000, "quantity": 2, "description": "something else", "expire": 123456789, "additionalData": "additional data here"}';
        $item = Item::fromJSON($aJSONStringContainingAnItem);

        /**
         * Correctly creates the item
         */
        $this->assertTrue($item instanceof Item);
        /**
         * Correctly sets the mandatory values
         */
        $this->assertEquals($item->getAmount(), 2000);
        $this->assertEquals($item->getQuantity(), 2);
        $this->assertEquals($item->getDescription(), 'something else');
        /**
         * Correctly sets default values
         */
        $this->assertEquals($item->getExpire(), 123456789);
        $this->assertEquals($item->getAdditionalData(), 'additional data here');
    }

    public function testThrowsIfDescriptionIsNotGiven()
    {
        $aJSONStringWithoutDescription = '{"amount": 5000, "quantity": 5}';
        $this->setExpectedException(\Exception::class, 'Description is not a string');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }
    public function testThrowsIfDescriptionIsNull()
    {
        $aJSONStringWithoutDescription = '{"amount": 5000, "quantity": 5, "description": null}';
        $this->setExpectedException(\Exception::class, 'Description is not a string');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }

    public function testThrowsIfAmountIsNotGiven()
    {
        $aJSONStringWithoutDescription = '{"description": "something pretty", "quantity": 5}';
        $this->setExpectedException(\Exception::class, 'amount must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }
    public function testThrowsIfAmountIsNull()
    {
        $aJSONStringWithoutDescription = '{"amount": null, "quantity": 5, "description": "something pretty"}';
        $this->setExpectedException(\Exception::class, 'amount must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }

    public function testThrowsIfAmountIsString()
    {
        $aJSONStringWithoutDescription = '{"amount": "55", "quantity": 5, "description": "something pretty"}';
        $this->setExpectedException(\Exception::class, 'amount must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }

    public function testThrowsIfQuantityIsNotGiven()
    {
        $aJSONStringWithoutDescription = '{"description": "something pretty", "amount": 5000}';
        $this->setExpectedException(\Exception::class, 'quantity must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }
    public function testThrowsIfQuantityIsNull()
    {
        $aJSONStringWithoutDescription = '{"amount": 5000, "quantity": null, "description": "something pretty"}';
        $this->setExpectedException(\Exception::class, 'quantity must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }

    public function testThrowsIfQuantityIsString()
    {
        $aJSONStringWithoutDescription = '{"amount": 66, "quantity": "5", "description": "something pretty"}';
        $this->setExpectedException(\Exception::class, 'quantity must be an Integer');
        $item = Item::fromJSON($aJSONStringWithoutDescription);
    }

    public function testThrowsIfQuantityIsLessThanZero()
    {
        $aJSONStringContainingAnItem = '{"amount": 2000, "quantity": -5, "description": "something valuable"}';
        $this->setExpectedException(\Exception::class, "quantity cannot be less than zero");
        $item = Item::fromJSON($aJSONStringContainingAnItem);
    }
}
