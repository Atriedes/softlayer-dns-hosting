        <div class="header">
            <h1 class="page-title">{{ Theme::place('pagetitle') }}</h1>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                    
          <div class="btn-toolbar">
              <a href="{{ URL::to('dashboard/add-domain')}}" class="btn btn-primary"><i class="icon-plus"></i> New Domain</a>
            <div class="btn-group">
            </div>
          </div>
            <div class="well">
                @if (Session::get('success_delete') === 1)

                    <div class="alert alert-info">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>Delete domain perform successfully</strong>
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
                <table class="table">
                  <thead>
                    <tr>
                      <th>Domain ID</th>
                      <th>Domain Name</th>
                      <th style="width: 26px;"></th>
                    </tr>
                  </thead>
                  <tbody>

                @foreach(Theme::place('body') as $domain)
                    <tr>
                      <td>{{ $domain->domain_id }}</td>
                      <td><a href="{{ URL::to('dashboard/domain-details') }}/{{ $domain->domain_id }}">{{ $domain->domain_name }}</a></td>
                      <td>
                          <a class="delete-button" href="#myModal" data-url="{{ URL::to('dashboard/delete-domain') }}/{{ $domain->domain_id }}" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                      </td>
                    </tr>
                @endforeach
                  </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ Theme::place('body')->links() }}
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
        <script type="text/javascript">
            $(".delete-button").click(function(){
                $('#delete-confirm').attr('href') = $(this).attr('data-url');
            });
        </script>