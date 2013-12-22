<div class="row">
  <div class="col-md-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Navigation</h4>
      </div>
      <ul class="list-group">
        <li class="list-group-item active"><a href="/files">Files</a></li>
        <li class="list-group-item"><a href="/photos">Photos</a></li>
      </ul>
    </div>
  </div>
  <div class="col-md-8">
    <table class="table table-hover table-bordered">
      <thead>
        <tr><th>Current directory: <?=$path;?></th></tr>
      </thead>
      <tbody>
        <?php
        foreach($files as $file) {
          $is_file = false;

          switch($file) {
            case ".":
              $href = '/files';
              break;
            case "..":
              $href = '/files'.$parent;
              break;
            default:
              if(!is_dir($dir.$path.'/'.$file)) {
                $is_file = true;
                $href = '/download'.$path.'/'.$file;
              } else {
                $href = '/files'.$path.'/'.$file;
              }
              break;
          }
          ?>
          <tr>
            <td><a href="<?=$href;?>"><?= $is_file ? '<span class="glyphicon glyphicon-file"></span> '.$file : '<span class="glyphicon glyphicon-folder-close"></span> '.$file;?></a></td>
          </tr>
        <?php
        }
        ?>
      </tobdy>
    </table>
  </div>
</div>
