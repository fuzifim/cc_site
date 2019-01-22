<!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="{{ route('changeavata') }}" enctype="multipart/form-data" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" >

            <div class="modal-header">
              <button class="close" data-dismiss="modal" type="button">&times;</button>
              <h4 class="modal-title" id="avatar-modal-label">Đổi avata</h4>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input class="avatar-src" name="avatar_src" type="hidden">
                  <input class="avatar-data" name="avatar_data" type="hidden">
                  <label for="avatarInput">Chọn file</label>
                  <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-9">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                    <div class="avatar-preview preview-md"></div>
                    <div class="avatar-preview preview-sm"></div>
                  </div>
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-9">
                    <div class="btn-group">
                      <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">Quay trái</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15 độ</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30 độ</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45 độ</button>
                    </div>
                    <div class="btn-group">
                      <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">Quay phải</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15 độ</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30 độ</button>
                      <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45 độ</button>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <button class="btn btn-primary btn-block avatar-save" type="submit">Hoàn tất</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->