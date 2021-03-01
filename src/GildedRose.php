<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {


            switch ($item->name) {

                case 'Aged Brie':

                    // "Aged Brie" actually increases in Quality the older it gets

                    ++$item->quality;

                    // The end of the day

                    --$item->sell_in;

                    // Once the sell by date has passed, Quality changes [degrades] twice as fast

                    if ($item->sell_in < 0) ++$item->quality;

                    $this->checkQualityLimits($item);

                    break;

                case 'Sulfuras, Hand of Ragnaros':

                    // "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
                    // "Sulfuras" is a legendary item and as such its Quality is 80 and it never alters.

                    $item->quality = 80;

                    break;

                case 'Backstage passes to a TAFKAL80ETC concert':

                    // "Backstage passes", like aged brie, increases in Quality as its SellIn value approaches;
                    // Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less but
                    // Quality drops to 0 after the concert

                    ++$item->quality;

                    if ($item->sell_in <= 0) {
                        $item->quality = 0;
                    }
                    elseif ($item->sell_in <= 5) {
                        $item->quality = $item->quality + 2;
                    }
                    elseif ($item->sell_in <= 10) {
                        $item->quality = $item->quality + 1;
                    }

                    // The end of the day

                    --$item->sell_in;

                    $this->checkQualityLimits($item);

                    break;

                case 'Conjured':

                    // "Conjured" items degrade in Quality twice as fast as normal items

                    --$item->quality;

                    // The end of the day

                    --$item->sell_in;
                    --$item->quality;
                    if ($item->sell_in < 0) --$item->quality;

                    break;

                default:

                    // The end of the day

                    --$item->sell_in;
                    --$item->quality;
                    if ($item->sell_in < 0) --$item->quality;

                    $this->checkQualityLimits($item);

                    break;
            }

        }

    }

    /**
     * Check Quality Limits
     * The Quality of an item is never negative
     * The Quality of an item is never more than 50
     */
    private function checkQualityLimits(Item $item): void
    {
        if ($item->quality < 0) $item->quality = 0;
        if ($item->quality > 50) $item->quality = 50;
    }

}
