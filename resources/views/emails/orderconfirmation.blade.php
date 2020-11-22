<html lang="en">

<head>
  <style>
    span {
      font-weight: bold;
      color: #00b3b3;
    }

    p {
      color: #333;
    }

    img {
      width: 50px;
      height: 100px;
    }

    .purchase-order {
      font-weight: bold;
    }

    .date {
      font-style: italic;
      color: rgb(118, 118, 118);
    }

    .total {
      font-weight: bold;
    }

    .order-number {
      font-weight: bold;
    }

    .price {
      font-weight: italic;
      color: #333;
    }

    .model {
      margin: 0;
    }

    .brand {
      margin: 0;
    }

    .estimated-delivery {
      color: #00b3b3;
      font-weight: bold;
      font-style: italic;
    }
  </style>
</head>

<body>
  Hi <span>{{ $purchase->first_name }}</span>
  <p>Thank you for shopping at the <span>BoardReserve</span>.</p>
  <p class="purchase-order">Here is your purchase Order:</p>
  <p class="date">Purchase Date: {{ $purchase->created_at }}</p>
  <p class="estimated-delivery">{{$shipping['estimated_delivery_date']}}</p>
  <p class="total">Total:${{ $purchase->price_total }} + shipping ${{ $shipping['shipping_price'] }}</p>
  <p class="order-number"><span>Order Number:</span>{{ $purchase->order_number }}</p>
  @foreach($snowboards as $snowboard)
  <img src="{{$snowboard['image']}}" />
  <p class="price">${{ $snowboard['price'] }}</p>
  <p class="brand">{{ $snowboard['brand'] }}</p>
  <p class="model">{{ $snowboard['model'] }}</p>
  @endforeach

  <p class="questions">Have any Questions? send an email to <span>boardreserve@gmail.com</span></p>
</body>

</html>