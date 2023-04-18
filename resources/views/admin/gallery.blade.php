@foreach($imagePosts as $imagePost)
  @php
    $servicePhotos = (file_exists($imagePost->image_path) && $imagePost->image_path) ? URL::asset($imagePost->image_path) : URL::asset('image\no image.jpg');
  @endphp
  <div class="col-sm-6 col-md-4 col-lg-3 item">
    <input type="checkbox" name="imageId" value="{{ $imagePost->id }}">
    <a href="{{route('post.create', ['id' => $imagePost->id] ) }}">Edit</a>
    <img class="img-fluid" src="{{$servicePhotos}}">
  </div>
@endforeach
