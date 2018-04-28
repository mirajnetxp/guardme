<!DOCTYPE html>
<html lang="en">
<head>
   @include('style')
</head>
<body>
    <!-- fixed navigation bar -->
    @include('header')

	<div class="clearfix"></div>
	<div class="video">
		<div class="clearfix"></div>
		<div class="headerbg">
			<div class="col-md-12" align="center"><h1>Wallet</h1></div>
		</div>
		<div class="container">
			<div class="height30"></div>
		<div class="err-msg" align="center">Your balance is : {{ $available_balance }} GBP</div>
		<div class="height30"></div>
		<table class="table table-stripped">
			<thead>
				<tr>
					<th>Title</th>
					<th>Debit/Credit</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
			@foreach($all_transactions as $trans)
				<tr style="@if($trans->debit_credit_type == 'debit') background: #90EE90; @else background: #FFB6C1;  @endif">
					<td>{{ $trans->title }}</td>
					<td>{{ $trans->debit_credit_type }}</td>
					<td>{{ $trans->amount }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		</div>
	</div>
    @include('footer')
</body>
</html>