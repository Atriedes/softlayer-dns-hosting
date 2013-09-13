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
          <li class="active"><a href="#soa" data-toggle="tab">SOA</a></li>
          <li><a href="#ns" data-toggle="tab">NS</a></li>
          <li><a href="#a" data-toggle="tab">A</a></li>
          <li><a href="#cname" data-toggle="tab">CNAME</a></li>
          <li><a href="#aaaa" data-toggle="tab">AAAA</a></li>
          <li><a href="#mx" data-toggle="tab">MX</a></li>
          <li><a href="#txt" data-toggle="tab">TXT</a></li>
          <li><a href="#srv" data-toggle="tab">SRV</a></li>
        </ul>
        
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane active in" id="soa">
           <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Refresh</th>
                    <th>Retry</th>
                    <th>Expire</th>
                    <th>Minimum</th>
                    <th>TTL</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('soa') as $row)
                    <tr>
                        <td>{{ $row['refresh'] }}</td>
                        <td>{{ $row['retry'] }}</td>
                        <td>{{ $row['expire'] }}</td>
                        <td>{{ $row['minimum'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="ns">
            @if (Session::get('type') == 'ns')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'ns') }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Address')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Host</th>
                    <th>Address</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('ns') as $row)
                    <tr>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/NS') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="a">
            @if (Session::get('type') == 'a')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'a') }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Point To')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Host</th>
                    <th>Point To</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('a') as $row)
                    <tr>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/A') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="cname">
            @if (Session::get('type') == 'cname')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'cname') }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Point To')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Host</th>
                    <th>Point To</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('cname') as $row)
                    <tr>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/CNAME') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="aaaa">
            @if (Session::get('type') == 'aaaa')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'aaaa') }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Point To')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Host</th>
                    <th>Point To</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('aaaa') as $row)
                    <tr>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/AAAA') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="mx">
            @if (Session::get('type') == 'mx')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'mx') }}
              {{ Form::select('priority', array('0' => '0',
               '1' => '1', '10' => '10', '20' => '20', '30' => '30'), '0',
               array('class' => 'input-small')) }}
               {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Goes To')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Priority</th>
                    <th>Host</th>
                    <th>Goes To</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('mx') as $row)
                    <tr>
                        <td>{{ $row['mx_priority'] }}</td>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/MX') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="txt">
            @if (Session::get('type') == 'txt')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'txt') }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-medium',
              'placeholder' => 'Name')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-medium',
              'placeholder' => 'Value')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Value</th>
                    <th>TTL</th>
                    <th style="width: 26px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('txt') as $row)
                    <tr>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['data'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                        <td>
                          <a href="{{ URL::to('dashboard/edit-record/TXT') }}/{{ $row['record_id'] }}"><i class="icon-pencil"></i></a>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-record') }}/{{ $row['record_id'] }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="srv">
            @if (Session::get('type') == 'srv')
              <?php foreach($errors->all() as $error)
              {

                  echo 
                  "<div class='alert alert-error'>
                      <button type='button' class='close' data-dismiss='alert'>×</button>
                      $error
                  </div>";

              }
              ?>

              @if (Session::get('success') == 1)
                <div class="alert alert-info">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('msg') }}</strong>
                </div>
              @endif

            @endif
            <form class="well form-inline" method="POST" action="{{ URL::to('dashboard/add-record') }}">  
              {{ Form::token() }}
              {{ Form::hidden('domainId', Theme::place('id')) }}
              {{ Form::hidden('type', 'srv') }}
              {{ Form::text('service', Input::old('service'), array('class' => 'input-small',
              'placeholder' => 'Service')) }}
              {{ Form::select('priority', array('tcp' => 'TCP',
              'udp' => 'UDP'), 'tcp',
               array('class' => 'input-small')) }}
               {{ Form::select('priority', array('0' => '0',
               '1' => '1', '10' => '10', '20' => '20', '30' => '30'), '0',
               array('class' => 'input-small')) }}
               {{ Form::text('weight', Input::old('weight'), array('class' => 'input-small',
              'placeholder' => 'Weight')) }}
              {{ Form::text('port', Input::old('port'), array('class' => 'input-small',
              'placeholder' => 'Port')) }}
              {{ Form::text('host', Input::old('host'), array('class' => 'input-small',
              'placeholder' => 'Host')) }}
              {{ Form::text('data', Input::old('data'), array('class' => 'input-small',
              'placeholder' => 'Target')) }}
              {{ Form::text('ttl', Input::old('ttl'), array('class' => 'input-small',
              'placeholder' => 'TTL')) }}
              <button type="submit" class="btn">Add</button>  
            </form>
            <div class="well">
              <table class="table">
                <thead>
                  <tr>
                    <th>Service</th>
                    <th>Protocol</th>
                    <th>Priority</th>
                    <th>Weight</th>
                    <th>Port</th>
                    <th>Host</th>
                    <th>Target</th>
                    <th>TTL</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (Theme::place('srv') as $row)
                    <tr>
                        <td>{{ $row['service'] }}</td>
                        <td>{{ $row['protocol'] }}</td>
                        <td>{{ $row['priority'] }}</td>
                        <td>{{ $row['weight'] }}</td>
                        <td>{{ $row['port'] }}</td>
                        <td>{{ $row['host'] }}</td>
                        <td>{{ $row['target'] }}</td>
                        <td>{{ $row['ttl'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel">Delete Confirmation</h3>
          </div>
          <div class="modal-body">
              <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete the domain?</p>
          </div>
          <div class="modal-footer">
              <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
              <a id="delete-confirm" href="#" class="btn btn-danger">Delete</a>
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