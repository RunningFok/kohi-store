@component('mail::message')
# Daily Sales Report

## Report Date: {{ $salesData['date'] }}

### Summary Statistics

- **Total Orders:** {{ $salesData['total_orders'] }}
- **Total Revenue:** ${{ number_format($salesData['total_revenue'], 2) }}
- **Total Items Sold:** {{ $salesData['total_items_sold'] }}

---

## Products Sold Today

@if(count($salesData['products_sold']) > 0)
@component('mail::table')
| Product Name | Quantity Sold | Revenue |
|:------------|:-------------:|--------:|
@foreach($salesData['products_sold'] as $product)
| {{ $product['name'] }} | {{ $product['quantity'] }} | ${{ number_format($product['revenue'], 2) }} |
@endforeach
@endcomponent
@else
No products were sold today.
@endif

---

## Orders Today

@if($salesData['total_orders'] > 0)
@foreach($salesData['orders'] as $order)
**Order #{{ $order->id }}** - ${{ number_format($order->total_amount, 2) }} - {{ $order->created_at->format('H:i') }}

@foreach($order->orderItems as $item)
- {{ $item->product->name ?? 'Unknown' }} × {{ $item->quantity }} = ${{ number_format($item->subtotal, 2) }}
@endforeach

---
@endforeach
@else
No orders were placed today.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
