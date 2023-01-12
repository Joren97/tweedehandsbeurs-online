<!DOCTYPE html>
<html>

<head>
    <title>Lijst {{$list->list_number}}</title>
</head>

<style>
    html {
        font-family: 'Roboto', sans-serif;
    }

    .table__products {
    width: 100%;
    border-collapse: separate !important;
    overflow: hidden;
    border-radius: 0.25rem 0.25rem 0 0;
    border-spacing: 0;
    border: 1px solid #e9ecef;

    }

    .table__products th {
      border: 1px solid #e9ecef;
      padding: 0.5rem;
      vertical-align: middle;
    }

    .table__products td {
      border: 1px solid #e9ecef;
      padding: 0.25rem;
      vertical-align: middle;
    }

    .table__products tbody tr:nth-child(2n) {
        background-color: #f5f5f5;
      }

    .table__products .product__data {
      padding-left: 1rem;
    }

    .table__products .product__number {
      text-align: center;
    }

    .table__products .product__buttons {
      display: flex;
      justify-content: space-evenly;      
    }
</style>

<body>
    <h1>Lijst {{$list->list_number}}</h1>

    <table class="table__products">
        <thead>
        <tr>
            <th class="product__number">#</th>
            <th class="product__data">Beschrijving</th>
            <th class="product__data">Vraagprijs</th>
            <th class="product__data">Verkoopprijs</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($list->products as $product)
        <tr>
            <td class="product__number">{{$product->product_number}}</td>
            <td class="product__data">{{$product->description}}</td>
            <td class="product__data">&euro;&nbsp;{{str_replace('.',',',$product->price->asking_price)}}</td>
            <td class="product__data">&euro;&nbsp;{{str_replace('.',',',$product->price->selling_price)}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</body>

</html>