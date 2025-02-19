@section('page-title', 'All Products - Travel Planet initial task')

<x-layout>
    <h1>All Products</h1>
    <a href="{{ route('products.create') }}">Create New Product</a><br /><br />
    @if ($products->isEmpty())
        <p>No products found.</p>
    @else
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Sold Date</th>
                <th>Installments</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->price }}</td>
                    <td><span class="utc-date">{{ $product->getSoldDateUTC() }}</span></td>
                    <td>
                        @foreach ($product->getPaymentInstallments() as $installment)
                            <p>{{ $installment->getPrice() }} => <span class="utc-date">{{ $installment->getDueDateUTC() }}</span></p>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}">View</a>
                        <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</x-layout>
