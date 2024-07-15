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
         <a href="#" class="btn btn-primary">Add Posts</a>
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
                  <a href="#" class="btn btn-success">Update</a>
                  <a href="#" class="btn btn-danger">Delete</a>
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
      Tile: ${post.title}
      <br>
      Description: ${post.description}
      <br>
      <img src="http://localhost:8000/uploads/${post.image}" width="150px"/>`;

    });
  
  
  }

  loadData();
  
});




}










</script>


    
</body>
</html>