<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObjects;

use Money\Money as BaseMoney;
use Money\Currency as CurrencyMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use Modules\Shared\Domain\ValueObjects\Currency;

final readonly class Money
{
    private BaseMoney $money;
    private Currency $currency;
    public function __construct(float|int|string $amount, Currency $currency)
    {
        try {
            $parser = new DecimalMoneyParser(new ISOCurrencies());
            $this->money = $parser->parse((string) $amount, new CurrencyMoney($currency->value));
        } catch (\Money\Exception\UnknownCurrencyException $e) {
            // This should not occur because the Currency enum enforces ISO 4217 compliance.
            throw new \InvalidArgumentException('Currency do not apply to ISO 4217');
        } catch (\Money\Exception\ParserException $e) {
            throw new \InvalidArgumentException('Invalid amount value');
        }

        if ($this->money->getAmount() <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        $this->currency = $currency;
    }

    public function getFormattedAmount(): string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $formatter->format($this->money) . ' ' . $this->currency->value;
    }

    public function getAmount(): float
    {
        return self::getFloatAmount($this->money);
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function multiply(float $multiplier): self
    {
        return new self(
            $this->getAmount() * $multiplier,
            $this->getCurrency()
        );
    }

    public function add(self $money): self
    {
        return new self(
            self::getFloatAmount($this->money->add($money->money)),
            $this->currency
        );
    }

    public function subtract(self $money): self
    {
        return new self(
            self::getFloatAmount($this->money->subtract($money->money)),
            $this->currency
        );
    }

    public function equals(Money $other): bool
    {
        return $this->money->equals($other->money);
    }

    public static function getFloatAmount(BaseMoney $baseMoney): float
    {
        $currencies = new ISOCurrencies();
        return (float) $baseMoney->getAmount() / pow(10, $currencies->subunitFor($baseMoney->getCurrency()));
    }

    public function __toString(): string
    {
        return $this->getFormattedAmount();
    }
}
