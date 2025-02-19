@yield('page-title', 'Create New Product')
<x-layout>
    <h1>Create New Product</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <input type="hidden" id="user_timezone" name="user_timezone">

        <label for="name">Name*</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"><br>

        <label for="type">Type*</label>
        <select id="type" name="type" required>
            @foreach (\Modules\Shared\Domain\ValueObjects\ProductType::cases() as $type)
                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ ucfirst($type->name) }}</option>
            @endforeach
        </select><br>

        <label for="priceAmount">Price Amount*</label>
        <input type="number" step="0.01" id="priceAmount" name="priceAmount" value="{{ old('priceAmount') }}"><br>

        <label for="priceCurrency">Price Currency*</label>
        <select id="priceCurrency" name="priceCurrency">
            @foreach (\Modules\Shared\Domain\ValueObjects\Currency::cases() as $currency)
                <option value="{{ $currency->value }}" {{ old('priceCurrency') == $currency->value ? 'selected' : '' }}>
                    {{ $currency->name }}
                </option>
            @endforeach
        </select><br>

        <label for="soldDate">Sold Date*</label>
        <input type="datetime-local" id="soldDate" name="soldDate" value="{{ old('soldDate') }}"><br>

        <button type="submit">Save</button>
        <a href="{{ route('products.index') }}">Back to All Products</a>
    </form>
</x-layout>
