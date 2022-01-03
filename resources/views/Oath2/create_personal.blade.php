<div>

 

<form method="GET" action="{{route('developers.OathPersonal')}}">
 @csrf
    <div class="container shadow p-3 mb-5 bg-white rounded">
        <label for="appName">Enter Application Name</label>
        <input type="text" name="app_name" class="form-control input-sm"  placeholder="App Name">
    <br>
        <label>Enter Redirect Uri</label>
        <input type="text" class="form-control input-sm" placeholder="Enter redirect url" name="redirect_uri">
   
<br>
    <label>Application Username</label>   
    <input type="number" class="form-control input-sm" placeholder="Application Username" name="user_id">
    <br>
    <button class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
