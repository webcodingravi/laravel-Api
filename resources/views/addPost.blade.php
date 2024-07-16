<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AddPost</title>
  
  {{-- Bootstrap-5 css file --}}
  <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
</head>
<body>
  <div class="addPost mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-8 bg-primary py-3 text-white mb-5 text-center">
        <h1>Create Post</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <form id="addForm">
            <input type="text" id="title" class="form-control mb-3" placeholder="Title..">

            <textarea id="description" cols="30" rows="10" class="form-control mb-3" placeholder="Write Note.."></textarea>

            <input type="file" id="image" class="form-control mb-3">

            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/posts" class="btn btn-info">Cancel</a>

          </form>
        </div>
      </div>
    </div>
  </div>

<script>
  var addform = document.querySelector("#addForm");
  addform.onsubmit = async(e) => {
     e.preventDefault();

     const token = localStorage.getItem('api_token');

     const title = document.querySelector("#title").value;
     const description = document.querySelector("#description").value;
     const image = document.querySelector("#image").files[0];

     var formData = new FormData();
          formData.append('title', title);
          formData.append('description', description);
          formData.append('image', image);

      let response = await fetch('/api/posts',{
      method: 'POST',
      body: formData,
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