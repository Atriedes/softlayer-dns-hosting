<div class="header">
    <h1 class="page-title">{{ Theme::place('pagetitle') }}</h1>
</div>

<div class="container-fluid">
  <div class="row-fluid">
      <div class="btn-toolbar">
        <div class="btn-group">
        </div>
      </div>
      <div class="well">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#home" data-toggle="tab">{{ Theme::place('pagetitle') }}</a></li>
        </ul>
        
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane active in" id="home">
            @if (Session::get('success') === 1)
              <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Add Domain Success</strong>
              </div>
            @else
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>
              @endif
              
            <form action="{{ URL::current() }}" method="POST" id="tab" class="well">
              {{ Form::token() }}
              <label>Domain Name</label>
              {{ Form::text('domain', Input::old('domain'), array('class' => 'input-xlarge',
              'placeholder' => 'example.com')) }}
              <label>IP Address</label>
              {{ Form::text('ip', Input::old('ip'), array('class' => 'input-xlarge',
              'placeholder' => '192.168.1.1')) }}
              <label> TTL </label>
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-xlarge',
              'placeholder' => '86400')) }}
              <div>
                <input type="submit" class="btn btn-primary" value="Add New">
              </div>
            </form>

          </div>
        </div>
      </div>      
      <footer>
          <hr>
          <!-- Purchase a site license to remove this link from the footer: http://www.portnine.com/bootstrap-themes -->
          <p class="pull-right">A <a href="http://www.portnine.com/bootstrap-themes" target="_blank">Free Bootstrap Theme</a> by <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
          

          <p>&copy; 2012 <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
      </footer>                 
  </div>
</div>