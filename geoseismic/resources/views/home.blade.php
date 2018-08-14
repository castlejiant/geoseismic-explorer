@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8">
      <div class="card size">
        <div class="card-header">Seismic Dashboard</div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          You are logged in!
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row justify-content-center align-items-center">
   

      @foreach($eqdb as $eqdbs)

      @php $mag = $eqdbs->mag; @endphp
      <div class="col-md-6">
      <div class="card size">
        <div class="card-header" style="
          background-color: @if($mag<=2.5)
          {{ 'green' }}
          @elseif($mag<4)
          {{ 'orange' }}
          @elseif($mag>=4.5)
          {{ 'red' }}
          @else
          {{ 'grey' }}
          @endif
          ;
          color: @if($mag<=2.5)
          {{ 'white' }}
          @elseif($mag<=4)
          {{ 'black' }}
          @elseif($mag>=5)
          {{ 'white' }}
          @else
          {{ 'white' }}
          @endif
        ;">{{ $eqdbs->event_id }}</div>
        <div class="card-body">
          <p>{{ $eqdbs->place }}</p>
          <p>Magnitude: {{ $eqdbs->mag }}</p>
          <p>Longitude: {{ $eqdbs->longitude }}</p>
          <p>Latitude: {{ $eqdbs->latitude }}</p>
          <p>Depth: {{ $eqdbs->depth }}</p>
          <p>Time:  {{ $eqdbs->time }}</p>
        </div>
      </div>
      </div>
      @endforeach
    
    @php echo $eqdb->render(); 
         toastr()->render();
    @endphp 
  </div>
</div>
@endsection

