@extends('layouts.backend')

@section('content')
    
     
<div id="page-wrapper">



  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Update Tag</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  
  <div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                {{ cms_notification($errors) }}

                <form role="form" method="POST" action="{{ route('tags.update',$tag->slug) }}"
                >
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ $tag->name }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </form>
    

            </div>
            <!-- /.col-lg-6 (nested) -->
        </div>
        <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
</div>


</div>
        <!-- /#page-wrapper -->
        @endsection
