@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Close Account</li>
        </ol>                       
        <h2 class="title">Close Account</h2>
    </div>
@endsection

@section('content')
    <div class="close-account text-center">
                <div class="delete-account section">
                    <h2>Delete Your Account</h2>
                    <h4>Are you sure, you want to delete your account?</h4>
                    <a href="" data-toggle="modal" data-target="#myModal" class="btn">Delete Account</a>
                    <!-- <a href="{{URL::to('delet-account')}}" onclick="return confirm('Are you sure you want to delete your account?');" class="btn">Delete Account</a>-->
                    <a href="{{URL::to('account')}}" class="btn cancle">Cancle</a>
                </div>          
            </div>

     <div class="modal" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Close Account</h4>
      </div>
      <form action="{{ route('ticket.close_account') }}" method="post">
      <div class="modal-body">
          {{csrf_field() }}
         <input type="hidden" name="category" value="5">
         <input type="hidden" name="title" value="Close account">
        <textarea name="message" class="form-control" rows="5" placeholder="Why close the account ?" required="true"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button  type="submit"  class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>       
@endsection