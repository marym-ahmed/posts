<div class="modal fade" id="updatePostModal" tabindex="-1" role="dialog" aria-labelledby="updatePostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatePostModalLabel">Update Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="update-post-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="title_en">Title (en)</label>
              <input type="text" class="form-control" id="title_en" name="title_en">
            </div>
            <div class="form-group">
              <label for="content_en">Content (en)</label>
              <textarea class="form-control" id="content_en" name="content_en"></textarea>
            </div>
            <div class="form-group">
              <label for="title_ar">Title (ar)</label>
              <input type="text" class="form-control" id="title_ar" name="title_ar">
            </div>
            <div class="form-group">
              <label for="content_ar">Content (ar)</label>
              <textarea class="form-control" id="content_ar" name="content_ar"></textarea>
            </div>
            <div class="form-group">
              <label for="thumbnail_display">Thumbnail</label>
              <input type="text" class="form-control" id="thumbnail_display" name="thumbnail_display" readonly>
              <img id="thumbnail_img" src="" alt="Thumbnail" width="100">
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select id="tags" name="tags[]" class="form-control" multiple>
                <!-- الخيارات ستضاف هنا -->
              </select>
            </div>
            <div class="form-group">
              <label for="new_tags">New Tags</label>
              <input type="text" class="form-control" id="new_tags" name="new_tags">
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
