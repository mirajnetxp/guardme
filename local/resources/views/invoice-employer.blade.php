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
    <section class="ad-details-page">
      <div class="container" style="width: 80%;">
          
        <div class="adpost-details post-resume">
          <div class="clearfix" style="margin-bottom: 10px;">
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
              <div>TRANSACTION NUMBER: @if($all_transactions != '') {{$all_transactions[0]->id}} @endif</div>
              <div>DATE: @if($all_transactions != '') {{date('d/m/Y',strtotime($all_transactions[0]->created_at))}} @endif</div>
              <DIV>TOTAL AMOUNT: {{$balance}}</DIV>
            </div>
          </div>
          <main class="clearfix">
            <table>
              <thead>
                <tr>
                  <th class="service">TYPE</th>
                  <th class="desc">DESCRIPTION</th>
                  <th class="unit">DATE</th>
                  <th class="qty">STATUS</th>
                  <th class="total">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach($all_transactions as $transaction)
                <tr>
                  <td class="service">{{$transaction->debit_credit_type}}</td>
                  <td class="desc">{{$transaction->title}}</td>
                  <td class="unit">{{date('d/m/Y',strtotime($transaction->created_at))}}</td>
                  <td class="qty">{{$transaction->credit_payment_status}}</td>
                  <td class="total">{{$transaction->amount}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="4" class="grand total">GRAND TOTAL</td>
                  <td class="grand total">{{$balance}}</td>
                </tr>
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
    </script>
  </body>
</html>