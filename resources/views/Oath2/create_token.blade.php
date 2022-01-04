<div>

<form method="GET" action="{{route('developers.redirect')}}">
 @csrf
    <div class="container shadow p-3 mb-5 bg-white rounded">
        <label for="appName">Enter Client ID</label>
        <input type="text" name="client_id" class="form-control input-sm"  placeholder="Client ID">
 <br>
    <label>Enter Client Secret</label>   
    <input type="text" class="form-control input-sm" placeholder="Client Secret" name="client_secret">
    <br>
    <button class="btn btn-primary">Generate Token</button>
    </div>
</form>
</div>
