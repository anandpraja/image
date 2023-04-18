<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImagePost;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImagePost $imagePost)
    {
      $this->imagePost  = $imagePost;
    }

    public function index(Request $request)
    {
      if (!$request->ajax()) {
        return view('admin.home');
      }

      $filter       = $request->filter;
      $imagePosts   = $this->imagePost;

      if ($filter != 'all') {
        $imagePosts  = $imagePosts->where('created_at', '<', Carbon::now()->subDays((int)$filter));
      }

      $imagePosts  = $imagePosts->paginate(12);
      $view = view('admin.gallery',compact('imagePosts'))->render();
      return response()->json(['status' => 200, 'html' => $view, 'paginate' =>(string) $imagePosts->links()]);
    }

    public function create(Request $request, $id = null)
    {
      return view('admin.create', compact('id'));
    }

    public function save(Request $request)
    {
      if($files=$request->file('photos')){
        $newPath = 'storage/photos/';
        if (!file_exists($newPath)) {
          mkdir($newPath, 0755, true);
        }
        foreach($files as $file){
          $name=$file->getClientOriginalName();
          $extention=$file->getClientOriginalExtension();
          $file->move($newPath,$name);

          $this->imagePost->updateOrCreate([
            'id' => $request->id
          ],[
            'user_id'     => Auth::id(),
            'image_name'  => $name,
            'image_ext'   => $extention,
            'image_path'   => "storage/photos/".$name
          ]);
        }
        return response()->json([
          'status'  => 200,
          'message' => 'Image upload successfully'
        ]);
      }
    }

    public function delete(Request $request)
    {
      $postIds = $request->selected;
      foreach ($postIds as $postId) {
        $imagePosts  = $this->imagePost->find($postId)->delete();
      }
      return response()->json([
        'status' => 200,
        'message' => 'Your post delete'
      ]);
    }
}
