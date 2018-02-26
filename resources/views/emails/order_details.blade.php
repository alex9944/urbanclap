@include('emails.header')

    <h2 align="center">ORDER DETAILS</h2>
    <table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:-90px;">
        <tr>
            <td style="padding:5px;" width="40%">
             <p><b> Order ID: {{$order_id}}</b></p>
             Order Date: {{$order_date}}<br/>
         </td>
         <td style="padding:5px;">
            <br/> <br/> <br/>
            <p><b>Billing Address</b></p>
            {{$b_name}}<br/>
            {{$b_address}}<br/>
            {{$b_city}},<br/>
            {{$b_state}}.<br/>
            {{$b_country}}-{{$b_pincode}}<br/>
        Phone: {{$mobile}}</td>
        <td style="padding:5px;">
            <br/> <br/> <br/>
            <p><b>Shipping Address</b></p>
            {{$s_name}}<br/>
            {{$s_address}}<br/>
            {{$s_city}},<br/>
            {{$s_state}},<br/>
            {{$s_country}}-{{$s_pincode}}<br/>
            Phone: {{$mobile}}
        </td>
    </tr>
</table>
<table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style=" border-collapse: collapse;margin-top:10px" >

    <thead >
        <tr>
            <th style="border: 1px solid black;padding:5px">Product</th>
            <th style="border: 1px solid black;padding:5px">Title</th>
            <th style="border: 1px solid black;padding:5px">Qty</th>
            <th style="border: 1px solid black;padding:5px">Price</th>
            <th style="border: 1px solid black;padding:5px">Tax</th>
            <th style="border: 1px solid black;padding:5px">Total</th>
        </tr>
    </thead>
    <tbody>
      @if($fooditem)
      @foreach($fooditem as $food)
      <?php $foodmenu=App\Models\FoodMenuItem::find($food['menu_item_id']);
      $item_name=$foodmenu->name;
      $item_type=$foodmenu->item_type;?>
      <tr>
       <td style="border: 1px solid black;padding:5px">{{$item_name}}
       </td>
       <td style="border: 1px solid black;padding:5px">{{$item_type}}</td>
       <td style="border: 1px solid black;padding:5px">{{$food['quantity']}}</td>
       <td style="border: 1px solid black;padding:5px">{{$food['unit_price']}}</td>
       <td style="border: 1px solid black;padding:5px"></td>
       <td style="border: 1px solid black;padding:5px">{{$food['total_amount']}}</td>
   </tr>
   @endforeach
   @endif
   @if($products)
   @foreach($products as $product)
   <?php $prod=App\Models\Shopproducts::find($product['product_id']);
   $pro_name=$prod->pro_name;
   $pro_info=$prod->pro_info;?>
   <tr>
    <td style="border: 1px solid black;padding:5px">{{$pro_name}}
    </td>
    <td style="border: 1px solid black;padding:5px">{{$pro_info}}</td>
    <td style="border: 1px solid black;padding:5px">{{$product['quantity']}}</td>
    <td style="border: 1px solid black;padding:5px">{{$product['unit_price']}}</td>
    <td style="border: 1px solid black;padding:5px"></td>
    <td style="border: 1px solid black;padding:5px">{{$product['total_amount']}}</td>
</tr>
@endforeach
@endif
<td></td>
<td><b>Total</b></td> 
<td style="border: 1px solid black;padding:5px">{{$total_qty}}</td>
<td style="border: 1px solid black;padding:5px">{{$total_amount}}</td>
<td style="border: 1px solid black;padding:5px"></td>
<td style="border: 1px solid black;padding:5px">{{$total_amount}}</td>
</tr>
<tr>
    <td colspan="6" style="font-size: 25px;padding:10px;"><b>Grand Total</b>
        <b style="float: right;">{{$total_amount}}</b>
    </td>
</tr>
</tbody>
</table>

@include('emails.footer')