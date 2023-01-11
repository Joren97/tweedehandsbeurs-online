<!DOCTYPE html>
<html>

<head>
    <title>Lijst {{$list->list_number}}</title>
</head>

<body>
    <h1>Lijst {{$list->list_number}}</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Beschrijving</th>
                <th>Vraagprijs</th>
                <th>Verkoopprijs</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list->products as $product)
            <tr>
                <td>{{$product->product_number}}</td>
                <td>{{$product->description}}</td>
                <td>{{$product->price->asking_price}}</td>
                <td>{{$product->price->selling_price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>