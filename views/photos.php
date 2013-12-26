<div class="row">
  <div class="col-md-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Navigation</h4>
      </div>
      <ul class="list-group">
        <li class="list-group-item active"><a href="/files">Files</a></li>
        <li class="list-group-item"><a href="/photos">Photos</a></li>
        <li class="list-group-item"><a href="/generate-thumbnails">Generate Thumbnails</a></li>
      </ul>
    </div>
  </div>
  <div class="col-md-10">
    <div class="row">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Current directory: <?=$path;?></th>
            <th width="10%"> Type </th>
            <th width="10%"> Size </th>
          </tr>
        </thead>
        <tbody>
          <?php
          $pics = array();
          foreach($files as $file) {
            $is_file = false;
            $href = "";

            switch($file) {
              case ".":
                $href = '/photos';
                break;
              case "..":
                $href = '/photos'.$parent;
                break;
              default:
                if(is_dir($dir.$path.'/'.$file)) {
                  $href = '/photos'.$path.'/'.$file;
                } elseif(getimagesize($dir.$path.'/'.$file)) {
                  $pics[] = '/download'.$path.'/'.$file;
                } else {
                  $is_file = true;
                  $href = '/download'.$path.'/'.$file;
                }
                break;
            }
            if(!empty($href)) {
              ?>
              <tr>
                <td><a href="<?=$href;?>"><?= $is_file ? '<span class="glyphicon glyphicon-file"></span> '.$file : '<span class="glyphicon glyphicon-folder-close"></span> '.$file;?></a></td>
                <td><?=filetype($dir.$path.'/'.$file);?></td>
                <td><?=filesize($dir.$path.'/'.$file);?></td>
              </tr>
              <?php
            }
          }
          ?>
        </tobdy>
      </table>
    </div>
    <div class="row">
      <div id="links">
        <?php
        foreach($pics as $pic) {
        ?>
          <div style="width: 200px; height: 200px;  overflow:hidden; float:left;">
            <a href="<?=$pic;?>" title="<?=$pic;?>" data-gallery>
              <img src="<?=$pic;?>" alt="pic" width="200px"/>
            </a>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
