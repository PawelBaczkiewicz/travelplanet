@yield('page-title', 'Product Details')
<x-layout>
    <h1>{{ $product->name }}</h1>
    <p><strong>Type:</strong> {{ $product->type }}</p>
    <p><strong>Price:</strong> {{ $product->price }}</p>
    <p><strong>Sold Date:</strong> <span class="utc-date">{{ $product->getSoldDateUTC() }}</span></p>
    <p><strong>Installments:</strong></p>
    @foreach ($product->getPaymentInstallments() as $installment)
        <p>{{ $installment->getPrice() }} => <span class="utc-date">{{ $installment->getDueDateUTC() }}</span></p>
    @endforeach
    <a href="{{ route('products.index') }}">Back to All Products</a>
</x-layout>


