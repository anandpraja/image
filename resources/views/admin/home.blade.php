@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-4 text-left">
      <div class="previous">
        <a href="{{ route('post.create') }}" class="btn btn-outline-info">Create new posts</a>
      </div>
    </div>
    <div class="col-4 text-left">
      <div class="next">
        <a href="javascript:void(0)" id="delete" class="btn btn-outline-danger">Delete</a>
      </div>
    </div>
    <div class="col-4 text-left">
      <div class="next">
        <select class="form-control" id="dateFilter" name="">
          <option value="all">All</option>
          <option value="7">7 day before</option>
          <option value="30">30 day before</option>
        </select>
      </div>
    </div>
  </div>
  <div class="photo-gallery">
    <div class="container">
      <div class="intro">
        <h2 class="text-center">Gallery</h2>
      </div>
      <div class="row photos" id="galleryView">
      </div>
    </div>
  </div>
  <div id="paginate">
  </div>
</div>
@endsection

@section('extrnalScript')
<script type="text/javascript">

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function(event){
    page = 1;
    selectFilter = 'all';
    getData(page, selectFilter);

    $(document).on('click', '.pagination a', function(event) {
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      getData(page, selectFilter);
    });

    $('#delete').click(function() {
      postsDelete();
    });

    $('#dateFilter').change(function() {
      var selectFilter = $("#dateFilter :selected").val();
      getData(page, selectFilter);
    });

    baguetteBox.run(".gallery", {
      animation: "slideIn"
    });
  });

  function getData(page, selectFilter) {
    $.ajax({
      url: "{{ route('post.home') }}" + "?page=" + page + "&filter=" + selectFilter,
      type: "get",
      success:function(response){
        if (response.status == 200) {
          $('#galleryView').html(response.html);
          $('#paginate').html(response.paginate);
        }
      }
    });
  };

  var selected = [];
  function postsDelete() {
    $("input[name='imageId']:checked").each(function() {
      selected.push($(this).val());
    });
    $.ajax({
      url: "{{ route('post.delete') }}",
      type: "post",
      data: {
        selected
      },
      success: function(response) {
        if (response.status == 200) {
          getData(page);
        }
      }
    });
  };

  baguetteBox.run(".gallery", {
    animation: "slideIn"
  });

</script>
@endsection
