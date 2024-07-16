<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    {{-- Bootstrap-5 css file --}}
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
</head>
<div class="main-section">
  <div class="container-fluid">
    <div class="content-section">
      <div class="content-heading text-center py-3 bg-primary text-white">
           <h2>All Posts</h2> 
      </div>
        <div class="col-md-6 offset-md-3 mt-5">
         <a href="/addPost" class="btn btn-primary">Add Posts</a>
         <button class="btn btn-warning" id="logout">Logout</button>

         <div class="allPosts mt-2" id="postContainer">
        


         </div>
      
  </div>
    </div>
  </div>
</div>







<!--Single post Modal -->
<div class="modal fade" id="singlePostModal" aria-labelledby="singlePostModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="singlePostModalLabel">Single Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--Update post Modal -->
<div class="modal fade" id="UpdatePostModal" aria-labelledby="UpdatePostLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="UpdatePostLabel">Update Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateForm">
      <div class="modal-body">
        <input type="hidden" id="PostId">

        <label for="">Title</label>
        <input type="text" id="PostTitle" class="form-control mb-3">

         <label for="">Description</label>
            <textarea id="postBody" cols="30" rows="10" class="form-control mb-3"></textarea>

            <img id="showImage" width="150px" /><br>

            <label for="">Upload Image</label>

            <input type="file" id="postImage" class="form-control mb-3">

      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" value="Save Changes" class="btn btn-primary">

      </div>
    </form>
    </div>
  </div>
</div>


<body>
    
{{-- Bootstrap-5 js --}}
    <script src="bootstrap/bootstrap.js"></script>
   
<script>
  document.querySelector("#logout").addEventListener('click', function() {
    const token = localStorage.getItem('api_token');
  
    fetch('/api/logout',{
      method: 'POST',
      headers:{
        'Authorization': `Bearer ${token}`,
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      window.location.href = "http://localhost:8000/login";
    });
  });



function loadData(){
  const token = localStorage.getItem('api_token');

  fetch('/api/posts',{
      method: 'GET',
      headers:{
        'Authorization': `Bearer ${token}`,
      }
    })
    .then(response => response.json())
    .then(data => {
      var allpost = data.data.posts;
      const postContainer = document.querySelector("#postContainer");
      
     var tabledata = `<table class="table table-bordered table-striped p-0">
            <thead >
              <tr class="bg-dark text-white">
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>`;
            allpost.forEach(post => {
              tabledata += `<tbody>
              <tr>
                <td><img src="/uploads/${post.image}" class="img-fluid" width="150"></td>
                <td>${post.title}</td>
                <td>${post.description}</td>
                <td><button class="btn btn-primary" data-bs-postId="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal">View</button>
                  <a href="#" class="btn btn-success" data-bs-postId="${post.id}" data-bs-toggle="modal" data-bs-target="#UpdatePostModal">Update</a>
                  <a href="#" onclick="deletPost(${post.id})" class="btn btn-danger">Delete</a>
                </td>


              </tr>
            </tbody>`;

            })
        
            tabledata +=`</table>`;
       
            postContainer.innerHTML =  tabledata;

    });
}

loadData()


// single post Model

var singlePostModal = document.querySelector("#singlePostModal");

if(singlePostModal) {
  singlePostModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  
  const modalBody = document.querySelector("#singlePostModal .modal-body");
  modalBody.innerHTML = "";

  const id = button.getAttribute('data-bs-postId');

  function loadData(){
  const token = localStorage.getItem('api_token');

  fetch(`/api/posts/${id}`,{
      method: 'GET',
      headers:{
        'Authorization': `Bearer ${token}`,
        'Content-Type' : 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      const post = data.data.post[0];
      modalBody.innerHTML = `
      <table class="table table-bordered">
        <tr class="bg-dark text-white">
       <th>Title:</th>
       <th>Description:</th>
        <th>Image:</th>
       </tr>

       <tr>  
       <td>${post.title}</td>
       <td>${post.description}</td>
       <td><img src="http://localhost:8000/uploads/${post.image}" width="150px"/></td>
            </tr>
        </table>`;
   

    });
  
  
  }

  loadData();
  
});




}




// update fecth single post 

var UpdatePostModal = document.querySelector("#UpdatePostModal");

if(UpdatePostModal) {
  UpdatePostModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  
  const id = button.getAttribute('data-bs-postId');

  function loadData(){
  const token = localStorage.getItem('api_token');

  fetch(`/api/posts/${id}`,{
      method: 'GET',
      headers:{
        'Authorization': `Bearer ${token}`,
        'Content-Type' : 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      const post = data.data.post[0];
      
      document.querySelector("#PostId").value = post.id;
      document.querySelector("#PostTitle").value = post.title;
      document.querySelector("#postBody").value = post.description;
      document.querySelector("#showImage").src = `uploads/${post.image}`;

    });

  }

  loadData();
  
});

}






// Update model

var updateForm = document.querySelector("#updateForm");

updateForm.onsubmit = async(e) => {
     e.preventDefault();

     const token = localStorage.getItem('api_token');

     const postId = document.querySelector("#PostId").value;
     const title = document.querySelector("#PostTitle").value;
     const description = document.querySelector("#postBody").value;

     var formData = new FormData();
          formData.append('id', postId);
          formData.append('title', title);
          formData.append('description', description);

          if(!document.querySelector("#postImage").files[0] == "") {
          const image = document.querySelector("#postImage").files[0];
          formData.append('image', image);

     }
          
      let response = await fetch(`/api/posts/${postId}`,{
      method: 'POST',
      body: formData,
      headers:{
        'Authorization': `Bearer ${token}`,
        'X-HTTP-Method-Override' : 'PUT'
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      window.location.href = "http://localhost:8000/posts";
    });

  }



  // Delete Post

  async function deletPost(postId) {
    const token = localStorage.getItem('api_token');

    let response = await fetch(`/api/posts/${postId}`,{
      method: 'DELETE',
      headers:{
        'Authorization': `Bearer ${token}`,
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      window.location.href = "http://localhost:8000/posts";
    });



  }







</script>


    
</body>
</html>