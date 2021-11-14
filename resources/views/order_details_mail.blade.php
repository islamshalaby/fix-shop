<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <h2>Order Details</h2>
  <h4>{{ $order->user->name }}</h4>
  <h4>Order number : {{ $order->order_number }}</h4>
  <table  border="1" style="width:100%">
    <tr>
      <th>Product</th>
      <th>Pin</th>
      <th>Serial Number</th>
      <th>Valid To</th>
    </tr>
    @if ($serials)
    @foreach ($serials as $row)
    <tr>
      <td>{{ $row->product->title_en }}</td>
      <td>{{ $row->serial }}</td>
      <td>{{ $row->serial_number }}</td>
      <td>{{ $row->valid_to }}</td>
    </tr>
    @endforeach 
    @endif
    
  </table>
</body>

</html>