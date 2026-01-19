@component('mail::message')
# Low Stock Alert

A product in your store is running low on stock.

## Product Information

**Product Name:** {{ $product->name }}

**Current Stock:** {{ $product->stock_quantity }} units

**Threshold:** {{ $threshold }} units

**Status:** {{ $product->status }}

@if($product->price)
**Price:** ${{ number_format($product->price, 2) }}
@endif

@component('mail::button', ['url' => '#'])
View Product
@endcomponent

Please consider restocking this product soon to avoid running out of inventory.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
