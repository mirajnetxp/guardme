<!DOCTYPE html>
<html lang="en">
  <head>
   @include('style')
    <link rel="stylesheet" href="{{asset('css/style.css')}}" media="all">
    <style type="text/css">
		button {
			background: #00a651;
			color: white !important;
			margin: 0;
			border: none;
			border-radius: 5px;
			padding-left: 20px;
			padding-right: 20px;
			height: 34px;
		}
    .dt-buttons {
      position: relative;
      top: -213px;
      float: right;
      right: 190px;
    }
	</style>
  </head>
  <body>
        <!-- fixed navigation bar -->
   
      @include('header')

    <!-- slider -->
    <div class="error text-center" style="display: none;">
        <p>NO DATA TO SHOW</p>
      </div>
    <section class="job-bg ad-details-page">
      <div class="container" style="width: 85%;">
      <div class="breadcrumb-section">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li><a href="{{ URL::to('/wallet-dashboard') }}">Wallet</a></li>
                 <li>Invoice</li>
            </ol>                       
            <h2 class="title">Wallet Invoice</h2>
        </div>
        <div class="section postdetails" style="border: 1px #cbc9c6 solid;">
          <div class="clearfix" style="margin: 10px;">
            <a href="" onclick="printPage();" class="btn pull-right">Print</a>
            @if(isset($user_id))
              <a href="{{ URL::to('/invoicepdf-child/'.$id.'/'.$user_id) }}" id="btnpdf" class="btn pull-right" style="width: 74px; background-color: #00a651;">PDF</a>
            @else
              <a href="{{ URL::to('/invoicepdf/'.$id) }}" id="btnpdf" class="btn pull-right" style="width: 74px; background-color: #00a651;">PDF</a>
            @endif
            <a class="btn btn-secondary" href="{{ URL::to('/wallet-dashboard') }}">&larr; Back to Wallet</a>
          </div>
          <div class="clearfix">
            <h1>INVOICE</h1>
            <div id="company" class="clearfix">
              <div>GuardME</div>
              <div>Andrav Technologies UK</div>
              <div>75 Archway Romford<br>Essex<br>RM3 7EH</div>
            </div>
            <div id="project">
              <div>NAME: {{$from->name}}</div>
              <div>TRANSACTION NUMBER: @if(count($all_transactions) != 0) {{$all_transactions[0]->id}} @endif</div>
              <div>DATE: @if(count($all_transactions) != 0) {{date('d/m/Y',strtotime($all_transactions[0]->created_at))}} @endif</div>
              <DIV>TOTAL AMOUNT: @if(count($all_transactions) != 0) {{$all_transactions[0]->amount / $all_transactions[0]->number_of_freelancers}} @endif</DIV>
            </div>
          </div>
          <main class="clearfix">
            <table class="display" id="table" data-page-length='25'>
              <thead>
                <tr class="hidden">
                  <th></th>
                  <th>INVOICE</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <tr class="hidden">
                  <td>NAME: {{$from->name}}</td>
                  <td></td>
                  <td></td>
                  <td>GuardME</td>
                </tr>
                <tr class="hidden">
                  <td>TRANSACTION NUMBER: @if(count($all_transactions) != 0) {{$all_transactions[0]->id}} @endif</td>
                  <td></td>
                  <td></td>
                  <td>Andrav Technologies UK</td>
                </tr>
                <tr class="hidden">
                  <td>DATE: @if(count($all_transactions) != 0) {{date('d/m/Y',strtotime($all_transactions[0]->created_at))}} @endif</td>
                  <td></td>
                  <td></td>
                  <td>75 Archway Romford</td>
                </tr>
                <tr class="hidden">
                  <td>TOTAL AMOUNT: @if(count($all_transactions) != 0) {{$all_transactions[0]->amount / $all_transactions[0]->number_of_freelancers}} @endif</td>
                  <td></td>
                  <td></td>
                  <td>Essex</td>
                </tr>
                <tr class="hidden">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>RM3 7EH</td>
                </tr>
                <tr>
                  <th class="service">TITLE</th>
                  <th class="unit">DATE</th>
                  <th class="qty">STATUS</th>
                  <th class="total">TOTAL</th>
                </tr>
                @foreach($all_transactions as $transaction)
                <tr>
                  <td class="service">{{$transaction->title}}</td>
                  <td class="unit">{{date('d/m/Y',strtotime($transaction->created_at))}}</td>
                  <td class="qty">@if($transaction->status == 'funded') ESCROW @else {{$transaction->status}} @endif</td>
                  <td class="total">{{$transaction->amount / $transaction->number_of_freelancers}}</td>
                </tr>
                <tr>
                  <td class="grand total"></td>
                  <td class="grand total">GRAND TOTAL</td>
                  <td class="grand total"></td>
                  <td class="grand total">{{$transaction->amount / $transaction->number_of_freelancers}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </main>
        </div>
      </div>
    </section>
    @include('footer')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#table').DataTable( {
              dom: 'Bfrtip',
              searching: false,
              sorting: true,
              buttons: [
                  'csv', 'excel',
              ],
              page_length: 50,
          });
          $('#table_paginate').css('display', 'none');
          $('#table_info').css('display', 'none');
      } );

      function printPage(){
        window.print();
      }

      var transactions = {!! json_encode($all_transactions) !!};
      console.log(transactions);
      if(transactions.length == 0){
        $('.ad-details-page').css('display','none');
        $('.error').css('display','block');
      }
    </script>
  </body>
</html>