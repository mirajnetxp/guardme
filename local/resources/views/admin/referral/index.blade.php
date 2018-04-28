@extends('admin/layout/default')
@section('title', 'Referral Items')
@section('content')
  <?php $url = URL::to("/"); ?>
        <div class="content">
            <div class="container-fluid">
                
                <div class="row">

                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                  
  @if(Session::has('success'))

      <div class="alert alert-success">

        {{ Session::get('success') }}

      </div>

  @endif


  
  
  @if(Session::has('error'))

      <div class="alert alert-danger">

        {{ Session::get('error') }}

      </div>

  @endif
        
          
                     <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="card">
                <div class="x_panel">
                  <div class="header">
                  <h4 class="title">Referral Items</h4>
                </div>
                
          <div align="right">
           
                  <div class="x_content">
                   
                  <div class="table-responsive">
                    <form class="form-horizontal form-label-left" role="form" method="POST"
                              action="{{ route('admin.itemcreate') }}" novalidate>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="title" name="title" required="required"
                                           class="form-control border-input col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Image
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="image" name="image" required="required"
                                           class="form-control border-input col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="number" id="price" name="price" required="required"
                                           class="form-control border-input col-md-7 col-xs-12">
                                </div>
                            </div>
                            {{ csrf_field() }}

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Add Item</button>
                                </div>
                            </div>
                        </form>
                        <div class="x_content">


                            <table id="datatable-responsive"
                                   class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->image }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                        </div>
                  </div>
          
                  </div>
                </div>
              </div>
        
        
        
     
      
      
      
        </div>
                    </div>
                  </div>
                </div>
              </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    
                    
                </div>
            </div>
        
        <script type="text/javascript">
          $(document).ready(function() {
              $('#datatable-responsive').DataTable();
          });
        </script>
@endsection
