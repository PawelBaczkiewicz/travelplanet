<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\Currency;

class MoneyTest extends TestCase
{
    public function test_can_create_money_with_valid_amount(): void
    {
        $money = new Money(100.50, Currency::PLN);

        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals(Currency::PLN, $money->getCurrency());
    }

    public function test_cannot_create_money_with_negative_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Money(-100, Currency::PLN);
    }

    public function test_can_add_money_of_same_currency(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(50, Currency::PLN);

        $sum = $money1->add($money2);

        $this->assertEquals(150, $sum->getAmount());
        $this->assertEquals(Currency::PLN, $sum->getCurrency());
    }

    public function test_can_substract_money_of_same_currency(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(20, Currency::PLN);

        $sum = $money1->subtract($money2);

        $this->assertEquals(80, $sum->getAmount());
        $this->assertEquals(Currency::PLN, $sum->getCurrency());
    }


    public function test_cannot_add_money_of_different_currencies(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(50, Currency::EUR);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Currencies must be identical');

        $money1->add($money2);
    }

    public function test_cannot_substract_money_of_different_currencies(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(20, Currency::USD);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Currencies must be identical');

        $money1->subtract($money2);
    }

    public function test_cannot_substract_if_result_is_negative(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(200, Currency::PLN);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be greater than 0');

        $money1->subtract($money2);
    }


    public function test_can_multiply_money(): void
    {
        $money = new Money(100, Currency::PLN);

        $result = $money->multiply(0.33);

        $this->assertEquals(33, $result->getAmount());
        $this->assertEquals(Currency::PLN, $result->getCurrency());
    }

    public function test_equals_returns_true_for_same_amount_and_currency(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(100, Currency::PLN);

        $this->assertTrue($money1->equals($money2));
    }

    public function test_equals_returns_false_for_different_amount(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(200, Currency::PLN);

        $this->assertFalse($money1->equals($money2));
    }

    public function test_equals_returns_false_for_different_currency(): void
    {
        $money1 = new Money(100, Currency::PLN);
        $money2 = new Money(100, Currency::EUR);

        $this->assertFalse($money1->equals($money2));
    }

    public function test_can_format_money_as_string(): void
    {
        $money = new Money(1234.56, Currency::PLN);

        $this->assertEquals('1234.56 PLN', (string)$money);
    }
}
