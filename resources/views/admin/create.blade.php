@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form id="photoId" method="post" enctype="multipart/form-data">
        <div class="card">
          <div class="card-header">Image upload</div>
          <div class="card-body">
            <div class="input-group">
              <input type="hidden" name="id" value="{{ $id ?? '' }}">
              <input type="file" class="form-control" id="photo" name="photos[]" accept="image/*" {{ isset($id) && $id ? '' : 'multiple'  }}>
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-outline-secondary" type="submit" id="saveImage">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('extrnalScript')
<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#photoId').submit(function(e) {
      e.preventDefault();
      let formData = new FormData(this);
      $.ajax({
        url: "{{ route('post.save') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success:function(response) {
          if (response.status == 200) {
            toastr.success("Successs Message", response.message);
            window.location = "{{ route('post.home') }}";
          } else {
            toastr.warning("Warning Message", 'Something error please try again');
          }
        }
      });
    });
  });
</script>
@endsection
