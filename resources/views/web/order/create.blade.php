@extends('web/layout')

@section('content')
    <section>
        <h1>Create Order</h1>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div>
                <label for="customer_id">Customer:</label>
                <select name="customer_id" id="customer_id">
                    {{-- @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach --}}
                </select>
            </div>

            <div>
                <label for="total">Total:</label>
                <input type="number" name="total" id="total" step="0.01">
            </div>

            <div>
                <label for="items">Items:</label>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody id="items-container">
                        <tr>
                            <td>
                                <select name="items[0][product_id]">
                                    {{-- @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach --}}
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" step="1" value="1"></td>
                            <td><input type="number" name="items[0][price]" step="0.01"></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-item">Add Item</button>
            </div>

            <button type="submit">Create Order</button>
        </form>
    </section>

    <script>
        // Add new item row to table
        let itemCounter = 1;
        const addItem = () => {
            const itemsContainer = document.querySelector('#items-container');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="items[${itemCounter}][product_id]">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[${itemCounter}][quantity]" step="1" value="1"></td>
                <td><input type="number" name="items[${itemCounter}][price]" step="0.01"></td>
            `;
            itemsContainer.appendChild(newRow);
            itemCounter++;
        };

        document.querySelector('#add-item').addEventListener('click', addItem);
    </script>
@endsection
