<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data['posts'] = Post::all();

      return $this->sendResponse($data, "All Post Data");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatePost = Validator::make($request->all(),
        [
          'title' => 'required',
          'description' => 'required',
          'image' => 'required|mimes:png,jpg,jpeg,gif',

        ]);

        if($validatePost->fails()){
      

        return $this->sendError($data, "Validation Error", $validatePost->errors()->all());
    }

    $img = $request->image;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.' .$ext;
    $img->move(public_path().'/uploads',$imageName);
    
    $Post = Post::create([
          'title' => $request->title,
          'description' => $request->description,
          'image' => $imageName,


    ]);

 

    return $this->sendResponse($Post, "Post Created Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $data['post'] = Post::select('id', 'title', 'description', 'image')
       ->where(['id' => $id])->get();


    return $this->sendResponse($data, "Your single Post");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatePost = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,gif',
  
          ]);
  
          if($validatePost->fails()){
        
          return $this->sendError($data, "Validation Error", $validatePost->errors()->all());

      }

      $postImage = Post::select('id','image')->where(['id' => $id])->get();

      if($request->image != '') {
        $path = public_path().'/uploads/';

        if($postImage[0]->image != '' && $postImage[0]->image != null){
            $old_file = $path .$postImage[0]->image;

            if(file_exists($old_file)){
                unlink($old_file);
            }
        }


        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time().'.' .$ext;
        $img->move(public_path().'/uploads',$imageName);


      }else{
        $imageName = $postImage->image;
      }

  
     
      
      $Post = Post::where(['id' => $id])->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
  
  
      ]);
  
  
      return $this->sendResponse($Post, "Post Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $imagePath = Post::select('image')->where('id', $id)->get();
        $filePath = public_path().'/uploads/'.$imagePath[0]['image'];
           
        unlink($filePath);


        $post = Post::where('id', $id)->delete();

  

        return $this->sendResponse($post, "Your Post has been removed");
    }
}
