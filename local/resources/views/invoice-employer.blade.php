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
              <div>TRANSACTION NUMBER: @if($all_transactions != '') {{$all_transactions[0]->id}} @endif</div>
              <div>DATE: @if($all_transactions != '') {{date('d/m/Y',strtotime($all_transactions[0]->created_at))}} @endif</div>
              <DIV>TOTAL AMOUNT: {{$balance}}</DIV>
            </div>
          </div>
          <main class="clearfix">
            <table>
              <thead>
                <tr>
                  <th class="desc">DESCRIPTION</th>
                  <th class="service">SLOT</th>
                  <th class="unit">DATE</th>
                  <th class="qty">STATUS</th>
                  <th class="total">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach($all_transactions as $transaction)
                  @if($transaction->title == 'Job Fee')
                    @foreach($transaction->user_id as $i => $user)
                    <tr>
                      <td class="desc">{{$transaction->title}}</td>
                      <td class="service"><a href="{{url('invoice').'?user_id='.$user->applied_by.'&id='.$id}}">SLOT {{$i + 1}}</a></td>
                      <td class="unit">{{date('d/m/Y',strtotime($transaction->created_at))}}</td>
                      <td class="qty">{{$transaction->status}}</td>
                      <td class="total">{{$transaction->amount/$transaction->number_of_freelancers}}</td>
                    </tr>
                    @endforeach
                  @else
                  <tr>
                    <td class="qty">@if($transaction->title == 'Admin Fee') Commission @else {{$transaction->title}} @endif</td>
                    <td class="service"></td>
                    <td class="unit">{{date('d/m/Y',strtotime($transaction->created_at))}}</td>
                    <td class="qty">{{$transaction->status}}</td>
                    <td class="total">{{$transaction->amount/$transaction->number_of_freelancers}}</td>
                  </tr>
                  @endif
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

      function invoice(user_id, job_id){
        window.location = "{{url('invoice?user')}}"+"?user_id="+user_id+"&id="+job_id;
      }
    </script>
  </body>
</html>