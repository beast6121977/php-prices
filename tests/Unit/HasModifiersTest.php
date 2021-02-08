<?php

namespace Tests\Unit;

use Brick\Money\Money;
use Whitecube\Price\Price;
use Whitecube\Price\Modifier;
use Tests\Fixtures\AmendableModifier;
use Tests\Fixtures\NonAmendableModifier;
use Tests\Fixtures\CustomAmendableModifier;

it('can add a callable modifier', function() {
    $price = Price::EUR(500, 2)->addModifier('custom', function($modifier) {
        $modifier->add(100);
    });

    expect($price)->toBeInstanceOf(Price::class);
    expect($price->exclusive()->__toString())->toBe('EUR 12.00');
});

it('can add a modifier instance', function() {
    $modifier = (new Modifier)->multiply(2)->subtract(200);

    $price = Price::EUR(500, 3)->addModifier('custom', $modifier);

    expect($price->exclusive()->__toString())->toBe('EUR 24.00');
});

// it('can add a custom amendable modifier instance', function() {
//     $modifier = new AmendableModifier();

//     $price = Price::EUR(500)->addModifier($modifier);

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(625)->equals($price->exclusive()));
// });

// it('can add a custom amendable modifier classname', function() {
//     $price = Price::EUR(500)->addModifier(AmendableModifier::class);

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(625)->equals($price->exclusive()));
// });

// it('can add a custom amendable modifier classname with custom arguments', function() {
//     $price = Price::EUR(500)->addModifier(CustomAmendableModifier::class, Money::EUR(250));

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(750)->equals($price->exclusive()));
// });

// it('can add a numeric modifier', function() {
//     $price = Price::EUR(500)->addModifier('-100');

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(400)->equals($price->exclusive()));
// });

// it('can add a Money modifier instance', function() {
//     $price = Price::EUR(500)->addModifier(Money::EUR(150));

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(650)->equals($price->exclusive()));
// });

// it('cannot add a NULL modifier', function() {
//     $this->expectException(\InvalidArgumentException::class);

//     Price::EUR(500)->addModifier(null);
// });

// it('cannot add an invalid modifier', function() {
//     $this->expectException(\InvalidArgumentException::class);

//     Price::EUR(500)->addModifier(['something','unusable']);
// });

// it('cannot add a non-PriceAmendable modifier instance', function() {
//     $this->expectException(\InvalidArgumentException::class);

//     $modifier = new NonAmendableModifier();

//     Price::EUR(500)->addModifier($modifier);
// });

// it('can add a tax modifier', function() {
//     $price = Price::EUR(500)->addTax(function(Money $value) {
//         return $value->plus(Money::EUR(50));
//     });

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(550)->equals($price->exclusive()));
// });

// it('can add a discount modifier', function() {
//     $price = Price::EUR(500)->addDiscount(function(Money $value) {
//         return $value->minus(Money::EUR(50));
//     });

//     assertInstanceOf(Price::class, $price);

//     assertTrue(Money::EUR(450)->equals($price->exclusive()));
// });

// it('can apply modifiers before computing VAT', function() {
//     $price = Price::EUR(500)
//         ->setVat(10)
//         ->addModifier(CustomAmendableModifier::class, Money::EUR(100))
//         ->addModifier(AmendableModifier::class);

//     $vat = $price->vat();

//     assertTrue(Money::EUR(63)->equals($vat));
//     assertTrue(Money::EUR(788)->equals($price->inclusive()));
// });

// it('can return whole modification history', function() {
//     $price = Price::EUR(500)
//         ->addModifier(CustomAmendableModifier::class, Money::EUR(100))
//         ->addModifier(AmendableModifier::class);

//     $history = $price->modifications();

//     assertTrue(is_array($history));
//     assertEquals(2, count($history));

//     assertEquals('foo-bar', $history[0]['key'] ?? null);
//     assertTrue(Money::EUR(125)->equals($history[0]['amount']));

//     assertEquals('bar-foo', $history[1]['key'] ?? null);
//     assertTrue(Money::EUR(100)->equals($history[1]['amount']));

//     assertTrue(Money::EUR(725)->equals($price->exclusive()));
// });

// it('can return filtered modification history', function() {
//     $price = Price::EUR(500)
//         ->addModifier(CustomAmendableModifier::class, Money::EUR(100))
//         ->addModifier(AmendableModifier::class)
//         ->addDiscount(-100);

//     $history = $price->modifications(Modifier::TYPE_DISCOUNT);

//     assertTrue(is_array($history));
//     assertEquals(1, count($history));

//     assertTrue(Money::EUR(-100)->equals($history[0]['amount']));

//     assertTrue(Money::EUR(625)->equals($price->exclusive()));
// });