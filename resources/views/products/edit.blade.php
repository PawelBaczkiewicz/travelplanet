

@yield('page-title', 'Edit Product')
<x-layout>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" id="user_timezone" name="user_timezone">

        <label for="name">Name*</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required><br>

        <label for="type">Type*</label>
        <select id="type" name="type" required>
            @foreach (\Modules\Shared\Domain\ValueObjects\ProductType::cases() as $type)
                <option value="{{ $type->value }}" {{ old('type', $product->type->value) == $type->value ? 'selected' : '' }}>{{ ucfirst($type->name) }}</option>
            @endforeach
        </select><br>

        <label for="priceAmount">Price Amount*</label>
        <input type="number" step="0.01" id="priceAmount" name="priceAmount" value="{{ old('priceAmount', $product->price->getAmount()) }}" required><br>

        <label for="priceCurrency">Price Currency*</label>
        <select id="priceCurrency" name="priceCurrency" required>
            @foreach (\Modules\Shared\Domain\ValueObjects\Currency::cases() as $currency)
                <option value="{{ $currency->value }}" {{ old('priceCurrency', $product->price->getCurrency()->toString()) == $currency->value ? 'selected' : '' }}>{{ ucfirst($currency->name) }}</option>
            @endforeach
        </select><br>

        <label for="soldDate">Sold Date*</label>
        <input type="datetime-local" id="soldDate" name="soldDate" value="{{ old('soldDate', $product->soldDate->format('Y-m-d\TH:i')) }}" required><br>

        <button type="submit">Save</button>
        <a href="{{ route('products.index') }}">Back to All Products</a>
    </form>

</x-layout>






