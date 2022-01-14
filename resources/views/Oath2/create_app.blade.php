<div>
<form method="GET" action="{{route('developers.oathClients')}}">
 @csrf
    <div class="container shadow p-3 mb-5 bg-white rounded">
        <label for="appName">Enter Application Name</label>
        <input type="text" name="app_name" class="form-control input-sm"  placeholder="App Name">
    <br>
        <label>Enter Redirect Uri</label>
        <input type="text" class="form-control input-sm" value="{{config('momoAPI.localhost').'/developers/callback'}}" name="redirect_uri" readonly>
   
<br>
    <label>Application Username</label>   
    <input type="number" class="form-control input-sm" value="{{Auth::user()->nrc_number}}" name="user_id" readonly>
    <br>
    <button class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
