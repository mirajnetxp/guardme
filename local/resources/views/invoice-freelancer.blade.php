<!DOCTYPE html>
<html lang="en">
  <head>
   @include('style')
    <link rel="stylesheet" href="{{asset('css/style.css')}}" media="all">
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
          
        <div class="section postdetails" style="border: 1px #cbc9c6 solid;">
          <div class="clearfix" style="margin: 10px;">
            <a href="" onclick="printPage();" class="btn pull-right">Print</a>
            <!-- <a href="{{url('wallet/invoice/').'/'.$id.'?download=pdf'}}" class="btn pull-right">PDF</a> -->
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
            <table>
              <thead>
                <tr>
                  <th class="service">TITLE</th>
                  <th class="unit">DATE</th>
                  <th class="qty">STATUS</th>
                  <th class="total">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach($all_transactions as $transaction)
                <tr>
                  <td class="service">{{$transaction->title}}</td>
                  <td class="unit">{{date('d/m/Y',strtotime($transaction->created_at))}}</td>
                  <td class="qty">@if($transaction->status == 'funded') ESCROW @else {{$transaction->status}} @endif</td>
                  <td class="total">{{$transaction->amount / $transaction->number_of_freelancers}}</td>
                </tr>
                <tr>
                  <td colspan="3" class="grand total">GRAND TOTAL</td>
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
    <script type="text/javascript">
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