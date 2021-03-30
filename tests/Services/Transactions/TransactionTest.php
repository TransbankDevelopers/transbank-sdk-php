<?php

namespace Tests\Services\Transactions;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Transbank\Sdk\Services\Transactions\Transaction;

class TransactionTest extends TestCase
{
    public function test_dynamically_gets_property(): void
    {
        $transaction = new Transaction('foo', [
            'foo' => 'bar',
            'baz_quz' => 'quuz'
        ]);

        static::assertFalse(isset($transaction->bar));

        static::assertEquals('bar', $transaction->foo);
        static::assertTrue(isset($transaction->foo));

        static::assertEquals('quuz', $transaction->bazQuz);
        static::assertTrue(isset($transaction->bazQuz));
    }

    public function test_exception_when_property_does_not_exists(): void
    {
        $this->expectError();
        $this->expectErrorMessage('Undefined property: Transbank\Sdk\Services\Transactions\Transaction::$Foo');

        $transaction = new Transaction('foo', [
            'foo' => 'bar',
        ]);

        $transaction->Foo;
    }

    public function test_property_is_immutable(): void
    {
        $transaction = new Transaction('foo', [
            'foo' => 'bar',
        ]);

        $transaction->foo = 'quz';

        static::assertEquals('bar', $transaction->foo);

        unset($transaction->foo);

        static::assertEquals('bar', $transaction->foo);

        $transaction['foo'] = 'quz';

        static::assertEquals('bar', $transaction->foo);

        unset($transaction['foo']);

        static::assertEquals('bar', $transaction->foo);
    }

    public function test_dynamically_gets_value_with_method(): void
    {
        $transaction = new Transaction('foo', [
            'foo' => 'bar',
            'baz_quz' => 'qux_quuz',
            'FooBarQuz' => 'foo_bar_quz',
        ]);

        static::assertEquals('bar', $transaction->getFoo());
        static::assertEquals('qux_quuz', $transaction->getBazQuz());
        static::assertEquals('foo_bar_quz', $transaction->getFooBarQuz());
    }

    public function test_exception_when_method_does_not_exists(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method getfoo does not exist');

        $transaction = new Transaction('foo', [
            'foo' => 'bar',
        ]);

        $transaction->getfoo();
    }

    public function test_exception_if_uses_set_method(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method setFoo does not exist');

        $transaction = new Transaction('foo', [
            'foo' => 'bar',
        ]);

        $transaction->setFoo('quz');
    }

    public function test_accessible_as_array(): void
    {
        $transaction = new Transaction('foo', [
            'foo' => 'bar',
            'baz_quz' => 'qux_quuz',
            'FooBarQuz' => 'foo_bar_quz',
        ]);

        static::assertEquals('bar', $transaction['foo']);
        static::assertEquals('qux_quuz', $transaction['baz_quz']);
        static::assertEquals('foo_bar_quz', $transaction['FooBarQuz']);

        static::assertTrue(isset($transaction['foo']));
        static::assertFalse(isset($transaction['cougar']));
    }

    public function test_transaction_successful(): void
    {
        $transaction = new Transaction('foo', [
            'response_code' => 0
        ]);

        static::assertTrue($transaction->isSuccessful());

        $transaction = new Transaction('foo', [
            'response_code' => 1
        ]);

        static::assertFalse($transaction->isSuccessful());

        $transaction = new Transaction('foo', []);

        static::assertFalse($transaction->isSuccessful());

        $transaction = new Transaction('foo', [
            'TBK_ID_SESSION' => 'test',
            'TBK_ORDEN_COMPRA' => 'test'
        ]);

        static::assertFalse($transaction->isSuccessful());
    }

    public function test_transaction_with_details_is_successful(): void
    {
        $transaction = Transaction::createWithDetails('foo', [
            'details' => [
                [
                    'response_code' => 0
                ],
                [
                    'response_code' => 0
                ],
            ]
        ]);

        static::assertTrue($transaction->isSuccessful());

        $transaction = Transaction::createWithDetails('foo', [
            'details' => [
                [
                    'response_code' => 1
                ],
                [
                    'response_code' => 0
                ],
            ]
        ]);

        static::assertFalse($transaction->isSuccessful());

        $transaction = Transaction::createWithDetails('foo', [
            'details' => [
                [
                    'response_code' => 1
                ],
                [
                    'response_code' => 1
                ],
            ]
        ]);

        static::assertFalse($transaction->isSuccessful());
    }

    public function test_get_credit_card_number(): void
    {
        $transaction = new Transaction('foo', [
            'card_detail' => [
                'card_number' => 'XXXXXXXXXXXX6623'
            ]
        ]);

        static::assertEquals(6623, $transaction->getCreditCardNumber());

        $transaction = new Transaction('foo', [
            'card_detail' => [
                'card_number' => '6623'
            ]
        ]);

        static::assertEquals(6623, $transaction->getCreditCardNumber());

        $transaction = new Transaction('foo', [
            'card_detail' => [
                'card_number' => 6623
            ]
        ]);

        static::assertEquals(6623, $transaction->getCreditCardNumber());
    }

    public function test_serializes_to_json(): void
    {
        $jsonString = '{"foo":{"bar":"baz"}}';

        $transaction = new Transaction('foo', [
            'foo' => [
                'bar' => 'baz'
            ]
        ]);

        static::assertJson($transaction->toJson());
        static::assertJsonStringEqualsJsonString($jsonString, $transaction->toJson());
        static::assertEquals($jsonString, json_encode($transaction));
    }
}
